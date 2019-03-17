<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Socialite;
use Facebook\Facebook;
use App\Services\Facebook\FacebookInsight;

class FacebookController extends Controller
{
    private $fb;

    public function __construct(Facebook $fb, Request $request) {
        $this->fb = $fb;
        $this->fb->setDefaultAccessToken($request->pageToken);
    }
   
    public function home(){
        return "logged in";
    }

    public function getPageLikes(Request $request, $pageId, Facebook $fb){
        $since = $request->since;
        $until = $request->until;

        $facebookInsight = new FacebookInsight($fb, $pageId);

        $response = $facebookInsight->metric('page_fans')
                                    ->since($since)
                                    ->until($until)
                                    ->get();

        return response()->json($response);
    }

    public function getPageViews(Request $request, $pageId, Facebook $fb){
        $since = $request->since;
        $until = $request->until;
        
        $facebookInsight = new FacebookInsight($fb, $pageId);

        $response = $facebookInsight->metric('page_views_total')
                                    ->since($since)
                                    ->until($until)
                                    ->get();

        return response()->json($response);
    }

    public function getPageEngagements(Request $request, $pageId, Facebook $fb) {
        $since = $request->since;
        $until = $request->until;
        
        $facebookInsight = new FacebookInsight($fb, $pageId);

        $response = $facebookInsight->metric('page_post_engagements')
                                    ->since($since)
                                    ->until($until)
                                    ->get();

        return response()->json($response);
    }

    public function getPageImpressions(Request $request, $pageId, Facebook $fb){
        $since = $request->since;
        $until = $request->until;
        
        $facebookInsight = new FacebookInsight($fb, $pageId);

        $response = $facebookInsight->metric('page_impressions')
                                    ->since($since)
                                    ->until($until)
                                    ->get();

        return response()->json($response);
    }


    // get the 1st 5 page posts
    public function getPagePostsId(Request $request, $pageId, Facebook $fb){
        try{
            $graphEdge = $fb->get("/{$pageId}/posts?limit=5")->getGraphEdge();
            $response = $graphEdge->asArray();

            $posts = [];

            foreach ($response as $post) {
                array_push($posts, $post['id']);
            }

            return $posts;
        } catch (FacebookSDKException $e) {
            echo $e->getMessage();
        }
    }
    
    // get the 1st 5 page posts and its info
    // 341693319367148_944388825764258?fields=insights.metric(post_reactions_wow_total).period(lifetime)
    public function getPagePostsDetails(Request $request, $pageId, Facebook $fb){
        $postsId = $this->getPagePostsId($request, $pageId, $fb);

        $postsDetails = [];
        foreach($postsId as $postId){
            try{
                $graphEdge = $fb->get("/{$postId}?fields=created_time,story,type,targeting,shares,comments.summary(1),link,insights.metric(post_impressions,post_engaged_users,post_reactions_like_total,post_reactions_wow_total,post_reactions_love_total, post_reactions_haha_total,post_reactions_sorry_total,post_reactions_anger_total)")->getGraphNode();
                // return response()->json($graphEdge->asArray());
                array_push($postsDetails, $graphEdge->asArray());
            } catch (FacebookSDKException $e) {
                echo $e->getMessage();
            }
        }
        
        dd($postsDetails);  
    }
}
