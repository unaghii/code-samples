<?php
/**
* A straightforward example of how I usually set custom post meta
* (with metabox.io, CMB2 and/or other frameworks)
*
* Client: Music therapy website
*/
function unaghii_get_meta_box($meta_boxes){

	// Theme prefix
	global $theme_prefix;

	// Classes array
	$classes_options = array();
	$classes_array = get_posts(array("post_type" => "turmas", "orderby" => "title", "order" => "ASC", "posts_per_page" => -1));
	if(!empty($classes_array)){
		foreach($classes_array as $class){
			$classes_options[$class->ID] = $class->post_title;
		}
	}

	// Events
	$meta_boxes[] = array(
		'id' 			=> 'events_metabox',
		'title'			=> 'Opções do evento',
		'post_types'	=> array('agenda'),
		'context'		=> 'after_editor',
		'priority'		=> 'high',
		'autosave'		=> 'false',
		'fields'		=> array(
			array(
				'id'		=> $theme_prefix . 'date',
				'type'		=> 'date',
				'name'		=> 'Data',
				'desc'		=> 'Data do evento',
				'timestamp'	=> 'true',
			),
			array(
				'id'		=> $theme_prefix . 'date_end',
				'type'		=> 'date',
				'name'		=> 'Data - Término',
				'desc'		=> 'Data de término do evento',
				'timestamp'	=> 'true',
			),
			array(
				'id'		=> $theme_prefix . 'time',
				'name'		=> 'Horário',
				'type'		=> 'time',
				'desc'		=> 'Horário do evento',
			),
			array(
				'id'		=> $theme_prefix . 'address',
				'type'		=> 'text',
				'name'		=> 'Endereço',
				'desc'		=> 'Endereço do evento',
			),
			array(
				'id'		=> $theme_prefix . 'url',
				'type'		=> 'url',
				'name'		=> 'URL',
				'desc'		=> 'URL do evento',
			),
		),
	);

	// Classes
	$meta_boxes[] = array(
		'id' 			=> 'content_metabox',
		'title'			=> 'Opções do conteúdo',
		'post_types'	=> array('acesso-restrito'),
		'context'		=> 'after_editor',
		'priority'		=> 'high',
		'autosave'		=> 'false',
		'fields'		=> array(
			array(
				'name'		=> 'Turmas permitidas',
				'desc'		=> 'Selecione as turmas que podem ter acesso a esse conteúdo.',
				'id'		=> $theme_prefix . 'allowed_classes',
				'type'		=> 'checkbox_list',
				'options'	=> $classes_options
			),
		)
	);

	// Courses
	$meta_boxes[] = array(
		'id' 			=> 'courses_metabox',
		'title'			=> 'Opções do curso',
		'post_types'	=> array('cursos'),
		'context'		=> 'after_editor',
		'priority'		=> 'high',
		'autosave'		=> 'false',
		'fields'		=> array(
			array(
				'id'		=> $theme_prefix . 'subtext',
				'type'		=> 'text',
				'name'		=> 'Texto destaque na listagem de cursos'
			),
			array(
				'id'		=> $theme_prefix . 'full_width_thumb',
				'type'		=> 'checkbox',
				'name'		=> 'Thumb deve ocupar a largura toda?'
			),
			array(
				'id'		=> $theme_prefix . 'page_img',
				'type'		=> 'image',
				'name'		=> 'Imagem do topo da página do curso',
				'desc'		=> 'Caso nenhuma imagem seja selecionada, será usada a imagem destacada'
			),
			array(
				'id'		=> $theme_prefix . 'show_signup_form',
				'type'		=> 'checkbox',
				'name'		=> 'Mostrar formulário de inscrição?',
			),
			array(
				'id'		=> $theme_prefix . 'pagseguro_link',
				'type'		=> 'text',
				'name'		=> 'Link do PagSeguro do formulário.'
			),
			array(
				'id'		=> $theme_prefix . 'email_student_content',
				'type'		=> 'textarea',
				'name'		=> 'Conteúdo do email de inscrição - Aluno',
				'desc'		=> 'O primeiro parágrafo do email, no código, começará com "Olá, nome do aluno,".'
			),
			array(
				'id'		=> $theme_prefix . 'email_student_title',
				'type'		=> 'text',
				'name'		=> 'Título do email de inscrição - Aluno'
			),
			array(
				'id'		=> $theme_prefix . 'email_admin_content',
				'type'		=> 'textarea',
				'name'		=> 'Conteúdo do email de inscrição - Admin',
				'desc'		=> 'Conteúdo do email de notificação que o admin receberá quando o aluno fizer a inscrição. O email conterá no código o nome e email do aluno.'
			),
			array(
				'id'		=> $theme_prefix . 'email_admin_title',
				'type'		=> 'text',
				'name'		=> 'Título do email de inscrição - Admin',
				'desc'		=> 'Título do email de notificação que o admin receberá quando o aluno fizer a inscrição.'
			),
			array(
				'id'		=> $theme_prefix . 'mailchimp_list_ID',
				'type'		=> 'text',
				'name'		=> 'Mailchimp - ID da lista',
				'desc'		=> 'Se deixado em branco o default será "feda7185d6".'
			),
		),
	);

	// Slides
	$meta_boxes[] = array(
		'id' 			=> 'slides_metabox',
		'title'			=> 'Opções do slide',
		'post_types'	=> array('slides'),
		'context'		=> 'after_editor',
		'priority'		=> 'high',
		'autosave'		=> 'false',
		'fields'		=> array(
			array(
				'id'	=> $theme_prefix . 'link',
				'type'	=> 'text',
				'name'	=> 'Link',
				'desc'	=> 'Link do slide.',
			),
			array(
				'id'	=> $theme_prefix . 'blank',
				'type'	=> 'checkbox',
				'name'	=> 'O link deve abrir em outra aba?',
			)
		),
	);

	return $meta_boxes;
}
add_filter('rwmb_meta_boxes', 'unaghii_get_meta_box');