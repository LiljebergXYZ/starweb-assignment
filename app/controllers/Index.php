<?php

namespace App;

use Nano\Controller;
use Nano\View;

class Index extends Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Index page
     *
     * @return void
     */
    public function actionIndex() {
        $posts = Post::all();
        View::render('index', array('posts' => $posts));
    }

    /**
     * Page for adding a new entry
     *
     * @return void
     */
    public function actionAdd() {
        $view = 'add'; // The view we'll use to render
        $data = array(); // Data to pass on to view

        // Check if request method is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            View::render($view, $data);
            return;
        }

        // Make sure that all POST parameters are set
        if(!isset($_POST['title']) || !isset($_POST['description'])) {
            $data['error'] = 'Det fattas post parametrar';
            View::render($view, $data);
            return;
        }

        // Safely retrieve title and description (also strips html tags)
        $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
        $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

        // Verify that title does not contain numbers
        if(preg_match('~[0-9]~', $title) === 1) {
            $data['error'] = 'Titel får inte innehålla siffror';
            View::render($view, $data);
            return;
        }

        // Create new post
        $post = new Post();
        $post->title = $title;
        $post->description = $description;
        $post->save();
        $data['post'] = $post;

        //Render the add view
        View::render($view, $data);
    }

    /**
     * Page to edit the post
     *
     * @param integer $id
     * @return void
     */
    public function actionEdit($id = 0) {
        $view = 'edit'; // The view we'll use to render
        $data = array(); // Data to pass on to view

        // Check if request method is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if($id !== 0) {
                $data['post'] = Post::get($id);
            }
            View::render($view, $data);
            return;
        }

        // Make sure that all POST parameters are set
        if(!isset($_POST['title']) || !isset($_POST['description'])) {
            $data['error'] = 'Det fattas post parametrar';
            View::render($view, $data);
            return;
        }

        // Safely retrieve title and description (also strips html tags)
        $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
        $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

        // Verify that title does not contain numbers
        if(preg_match('~[0-9]~', $title) === 1) {
            $data['error'] = 'Titel får inte innehålla siffror';
            View::render($view, $data);
            return;
        }

        $post = new Post();
        $post->id = $id;
        $post->title = $title;
        $post->description = $description;
        $post->save();
        $data['post'] = $post;
        $data['saved'] = true;

        //Render the add view
        View::render($view, $data);
    }

    public function actionMissing($uri = '') {
        http_response_code(404);
        View::render('404', array('uri' => $uri));
    }

}