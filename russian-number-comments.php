<?php
/*
Plugin Name: Russian Number Comments
Plugin URI: http://www.wordpressplugins.ru/critical/2-4-comment-fix.html
Description: Исправляет окончания в комментариях. Делает из "2 комментариев" - "2 комментария". Подробно об установке и настройке этого плагина вы можете прочитать на <a href="http://www.wordpressplugins.ru/critical/2-4-comment-fix.html">странице</a> плагина.
Version: 1.1
Author: Flector 
Author URI: https://profiles.wordpress.org/flector#content-plugins
*/

/*
Для использования активируйте плагин и вставьте в файлы шаблона строчку 

<?php if(function_exists('russian_comments')) { russian_comments('Комментировать', '% комментарий', '% комментариев', '% комментария', 'Комментировать статью &quot;%s&quot;','Комментарии закрыты'); } ?>

или краткий вариант со значениями по умолчанию:
<?php if(function_exists('russian_comments')) { russian_comments(); } ?>
*/

function comments_number_2_4( $zero = false, $one = false, $more = false, $twotofour = false, $deprecated = '' ) {
	global $id;
	$number = get_comments_number($id);

	if ( $number == 0) {
		$output = ( false === $zero ) ? __('Комментировать') : $zero; }
	elseif ((($number > 1) && ($number < 5)) || ((($number % 10) > 1) && (($number % 10) < 5)) && ($number > 20)) {
		$output = str_replace('%', $number, ( false === $twotofour ) ? __('% комментария') : $twotofour); }
	elseif ((($number > 20) && (($number % 10) == 1)) || ($number == 1)) {
		$output = str_replace('%', $number, ( false === $one ) ? __('% комментарий') : $one); }
	else {		
	$output = str_replace('%', $number, ( false === $more) ? __('% комментариев') : $more); }
	echo apply_filters('comments_number_2_4', $output, $number);
}

function comments_number_2_4_2( $zero = false, $one = false, $more = false, $twotofour = false, $deprecated = '' ) {
	global $id;
	$number = get_comments_number($id);

	if ($number == 0) {
		$output = 'Комментировать'; }
	elseif ((($number > 1) && ($number < 5)) || ((($number % 10) > 1) && (($number % 10) < 5)) && ($number > 20)) {
		$output = str_replace('%', $number, '% комментария'); }
	elseif ((($number > 20) && (($number % 10) == 1)) || ($number == 1)) {
		$output = str_replace('%', $number, '% комментарий'); }
	else {
		$output = str_replace('%', $number, '% комментариев'); }
	echo apply_filters('russify_comments_number', $output, $number);
}


function russian_comments($zero='Комментировать', $one='% комментарий', $more='% комментариев', $twotofour='% комментария', $titlelink='Комментировать статью &quot;%s&quot;',$none='Комментарии отключены') {
	global $id, $wpcommentspopupfile, $wpcommentsjavascript, $post, $wpdb;

	$number = get_comments_number($id);

	if ( 0 == $number && 'closed' == $post->comment_status && 'closed' == $post->ping_status ) {
		echo '<span' . ((!empty($CSSclass)) ? ' class="' . $CSSclass . '"' : '') . '>' . $none . '</span>';
		return;
	}

	if ( !empty($post->post_password) ) { // if there's a password
		if ($_COOKIE['wp-postpass_'.COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			echo(__('Введите пароль для доступа к записи'));
			return;
		}
	}

	echo '<a href="';
	if ($wpcommentsjavascript) {
		if ( empty($wpcommentspopupfile) )
			$home = get_option('home');
		else
			$home = get_option('siteurl');
		echo $home . '/' . $wpcommentspopupfile.'?comments_popup='.$id;
		echo '" onclick="wpopen(this.href); return false"';
	} else { // if comments_popup_script() is not in the template, display simple comment link
		if ( 0 == $number )
			echo get_permalink() . '#respond';
		else
			comments_link();
		echo '"';
	}

	if (!empty($CSSclass)) {
		echo ' class="'.$CSSclass.'"';
	}

	$title = attribute_escape(get_the_title());
	echo ' title="' . sprintf( ($titlelink), $title ) .'">';
	comments_number_2_4($zero, $one, $more,$twotofour, $number);
	echo '</a>';
	
	
}
add_filter('comments_popup_link','russian_comments');
add_filter('comments_number', 'comments_number_2_4_2');

?>