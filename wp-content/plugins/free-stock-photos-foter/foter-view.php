<?php

class FoterView {
    protected $oRequest;

    protected $cookies = array();
    protected $cookieName = 'wpfoter';

    protected $html = '';

    protected $urlPrefix = 'http://foter.com/';

    public function __construct() {
        $this->oRequest = new WP_Http;

        $this->cookies = isset($_COOKIE[$this->cookieName]) ? unserialize(stripslashes($_COOKIE[$this->cookieName])) : array();

    }

    public function __destruct() {

    }

    public function out() {
        print $this->html;
    }

    public function doAction() {
        $action = $_GET['action'];
        $params = $_GET + $_POST;
        $html = '';
        switch($action) {
            case 'test': $html = $this->test($params);break;
            case 'dashboard': $html = $this->dashboard($params);break;
            case 'box': $html = $this->box($params);break;
            case 'advsearch': $html = $this->advsearch($params);break;
            case 'settings': $html = $this->settings($params);break;
            case 'category': $html = $this->category($params);break;
            case 'photo': $html = $this->photo($params);break;
            case 'signin': $html = $this->signin($params);break;
            case 'search': $html = $this->search($params);break;
            case 'insert-media': $html = $this->insertMedia($params);break;
        }

        return $this->html = $html;
    }

    public function test($params=array()) {
        return $this->request($this->urlPrefix.'/testpage.php');
    }

    public function insertMedia($params=array()) {
        $args = array(
            'method'=>'POST',
            'body'=>$params,
        );
        if ($params['stockphoto']) {
            $json = $this->request($this->urlPrefix.'/wp/insert-photo/'.$params['stockphoto'].'/',$args);
        }else {
            $json = $this->request($this->urlPrefix.'/wp/insert-media/'.$params['photo'].'/'.$params['sec'].'/',$args);
        }
        $vars = json_decode($json,true);
        $html=$vars['html'];

        if ($vars['hosting'] == 'wordpress') {
            $photourl = $vars['image-src'];
            $photofilename = $vars['image-filename'];
            $time = current_time('mysql');
            if ( ! ( ( $uploads = wp_upload_dir($time) ) && false === $uploads['error'] ) ) {
                print 'error';
            }
            $filename = wp_unique_filename( $uploads['path'], $photofilename, null );

            $new_file = $uploads['path'] . "/$filename";

            $defaults = array('cookies' => $this->cookies);
            $args = wp_parse_args( array(), $defaults );
            $r = $this->oRequest->request($photourl,$args);
            $content = $r['body'];

            file_put_contents($new_file, $content);

            $stat = stat( dirname( $new_file ));
            $perms = $stat['mode'] & 0000666;
            @ chmod( $new_file, $perms );

            $new_url = $uploads['url'] . "/$filename";

            $html = str_replace($photourl,$new_url,$html);
            
            $attachment = array_merge( array(
		'post_mime_type' => $vars['attachment-type'],
		'guid' => $new_url,
		'post_parent' => $params['post_id'],
		'post_title' => $vars['post_title'],
		'post_content' => '',
            ), array() );

            if ( isset( $attachment['ID'] ) )
                unset( $attachment['ID'] );

            $id = wp_insert_attachment($attachment, $new_file, $params['post_id']);

            if ( !is_wp_error($id) ) {
                wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $new_file ) );
            }

        }

        return $this->sendToEditor(html_entity_decode(preg_replace('/[\r\n]+/',' ',$html)));
    }

    public function photo($params=array()) {
        if ($params['stockphoto']) {
            return $this->request($this->urlPrefix.'/wp/sphoto/'.$params['stockphoto'].'/',$params);
        } else {
            return $this->request($this->urlPrefix.'/wp/photo/'.$params['photo'].'/'.$params['sec'].'/',$params);
        }
    }

    public function category($params=array()) {
        return $this->request($this->urlPrefix.'/wp/category/'.$params['category'].'/'.($params['npage'] ? ($params['npage'].'/') : ''),$params );
    }

    public function search($params=array()) {
        $q = htmlspecialchars($params['q'], ENT_QUOTES, 'utf-8');
        return $this->request($this->urlPrefix.'/wp/search/?q='.urlencode($q).'&page='.($params['npage'] ? $params['npage'] : 1),$params );
    }

    public function dashboard($params=array()) {
        return $this->request($this->urlPrefix.'/wp/dashboard/',$params);
    }

    public function signin($params=array()) {
	$defaults = array('method' => 'POST');
        $r = wp_parse_args( $params, $defaults );
        return $this->request($this->urlPrefix.'/wp/signin/?signin[email]='.$params['signin']['email'].'&signin[password]='.$params['signin']['password'],$r);
    }

    public function box($params=array()) {
        return $this->request($this->urlPrefix.'/wp/box/',$params);
    }

    public function settings($params=array()) {
        return $this->request($this->urlPrefix.'/wp/settings/',$params);
    }

    public function advsearch($params=array()) {
        return $this->request($this->urlPrefix.'/wp/advsearch/',$params);
    }

    public function sendToEditor($content) {
        return '<script type="text/javascript">
/* <![CDATA[ */
var win = window.dialogArguments || opener || parent || top;
win.send_to_editor("'.addslashes($content).'");
/* ]]> */
</script>
';
    }

    public function request($url,$args=array()) {
        $k = 3;
        do {

            $url = $url. (preg_match('/\?/',$url) ? '&' : '?')
                    .'&wpurl='.urlencode(getenv('HTTP_REFERER') ? getenv('HTTP_REFERER') :plugin_dir_url( __FILE__ ))
                    .'&wp_foter_path='.urlencode(FOTER_PLUGIN_URL)
                    .($args['post_id'] ? '&post_id='.$args['post_id'] : '');

            $defaults = array('cookies' => $this->cookies, 'timeout'=>30 );
            $args = wp_parse_args( $args, $defaults );
            $r = $this->oRequest->request($url,$args);
            
        } while ($k-- && ($r instanceof Wp_Error));

        if ($r instanceof Wp_Error) {
            return 'foter connection error';
        }

        if (is_array($r['cookies']) && count($r['cookies'])) {
            $this->cookies = $r['cookies'];
        }

        setcookie($this->cookieName, serialize($this->cookies));
	return $r['body'];
    }

}

ob_start();

require_once('../../../wp-admin/admin.php');
if( !class_exists( 'WP_Http' ) )
    include_once( ABSPATH . WPINC. '/class-http.php' );

$view = new FoterView();
$html = $view->doAction();

wp_enqueue_style( 'media' );
wp_enqueue_style('foter.css');
wp_enqueue_script("jquery");
wp_enqueue_script('foter.js');

ob_end_flush();

$body_id = 'media-upload';

print wp_iframe(array($view,'out'),'image');

