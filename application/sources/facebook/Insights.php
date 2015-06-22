<?php
/**
 * Created by PhpStorm.
 * User: nelson.daza
 * Date: 17/12/2014
 * Time: 10:16 AM
 */

namespace sources\facebook;

require_once( __DIR__ . '/api/autoload.php' );

use Facebook\Entities\AccessToken;
use sources\DataSource;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use sources\ProjectProperties;

class Insights extends DataSource {

	public function getManageTabViews( ) {
		return array(
			lang('fb_insights_app_page_title') => 'manage/fb_config',
			lang('fb_insights_connect_page_title') => 'manage/fb_connect_test'
		);
	}

	public function getManageFormRules( ) {

		return array(
			array(
				'field' => 'fb_insights_field_app_id',
				'label' => 'lang:fb_insights_field_app_id_error',
				'rules' => 'trim|required|max_length[20]'
			),
			array(
				'field' => 'fb_insights_field_app_secret',
				'label' => 'lang:fb_insights_field_app_secret_error',
				'rules' => 'trim|required|max_length[50]'
			),
			array(
				'field' => 'fb_insights_field_permissions',
				'label' => 'lang:fb_insights_field_permissions_error',
				'rules' => 'trim|required|max_length[80]'
			)
		);

	}

	public function save( ) {

		$CI =& get_instance();

		$this->loadProperties( );
		$rules = $this->getManageFormRules( );

		$changed = false;
		foreach( $rules as $rule )  {
			$newValue = ( $CI->input->post( $rule['field'], true ) ? $CI->input->post( $rule['field'], true ) : null );
			$changed = $changed || ( $newValue != $this->getProperty( $rule['field'] ) );
			$this->setProperty( $rule['field'], $newValue );
		}

		if( $changed )
			$this->setProperty( 'fb_insights_active', 0 );
		else if( $CI->input->post( 'fb_insights_active', true ) == 1 )
			$this->setProperty( 'fb_insights_active', 1 );

		$this->saveProperties();

		return array();
	}

	public function canActivate( ) {
		return ( $this->getProperty( 'fb_insights_active' ) == 1 );
	}

	/**
	 *
	 * @See https://developers.facebook.com/docs/graph-api/reference/v2.2/insights#metrics
	 *
	 * @return array
	 */
	public function getMeasures( ) {
		return array(
			'page_impressions',
			'page_impressions_unique',
			'page_impressions_organic',
			'page_impressions_organic_unique',
			'page_impressions_viral',
			'page_impressions_viral_unique',
			'page_engaged_users',
			'page_fans_online',
			'page_fans_online_per_day',
			'page_fans',
			'page_fan_removes',
			'page_fan_removes_unique',
			'page_views',
			'page_views_unique',
			'page_views_external_referrals',
			'page_posts_impressions',
			'page_engaged_users',
		);
	}

	/**
	 * Testing purposes only for a total of page fans
	 *
	 * @return array $response This will be sent to client as a JSON
	 * response['data'] => A array/vector with only one element
	 *      response['data'][0]['fans'] => Total fans
	 *      response['data'][0]['time'] => Timestamp representing the moment of the measure was taken
	 *
	 */
	public function service_test_measure_page_fans( ) {

		$CI =& get_instance();
		$response = array( );
		$page = $CI->input->get('page');

		$this->loadProperties();

		FacebookSession::setDefaultApplication( $this->getProperty('fb_insights_field_app_id'), $this->getProperty('fb_insights_field_app_secret') );
		// This will use de page's access_token coz it is a temporal access
		$session = new FacebookSession( $page['access_token'] );

		try {
			$request = new FacebookRequest($session, 'GET', '/' . $page['id'] . '/insights/page_fans', array( 'since' => strtotime( date("Y-m-d")), 'until' => strtotime( date( "Y-m-d") . " +1 days" ) ));
			$fbResponse = $request->execute()->getGraphObject()->asArray();

			if( !empty( $fbResponse['data'] ) ) {
				$obj = $fbResponse['data'][0]->values[0];
				$response['data'] = array(
					array(
						'fans' => $obj->value,
						'time' => strtotime( $obj->end_time )
					)
				);
			}
			else {
				$response['error'] = array(
					'code' => 'STMPF',
					'type' => 'NotFoundError',
					'msg' => sprintf( lang('services_object_not_found'), 'Page Fans' )
				);
			}
		} catch ( \Exception $ex ) {
			$response['error'] = array(
				'code' => $ex->getCode(),
				'type' => ( method_exists($ex,'getErrorType') ? $ex->getErrorType() : 'Error' ),
				'msg' => $ex->getMessage()
			);
		}

		return $response;
	}

	public function service_test_measure_page_fans_online_per_day( ) {

		$CI =& get_instance();
		$response = array( );
		$page = $CI->input->get('page');

		$this->loadProperties();

		FacebookSession::setDefaultApplication( $this->getProperty('fb_insights_field_app_id'), $this->getProperty('fb_insights_field_app_secret') );
		// This will use de page's access_token coz it is a temporal access
		$session = new FacebookSession( $page['access_token'] );

		try {
			$request = new FacebookRequest($session, 'GET', '/' . $page['id'] . '/insights/page_fans_online_per_day', array( 'since' => strtotime( date("Y-m-d") . " -1 week"), 'until' => strtotime( date( "Y-m-d") ) ));
			$fbResponse = $request->execute()->getGraphObject()->asArray();

			if( !empty( $fbResponse['data'] ) ) {
				$response['data'] = array();
				foreach( $fbResponse['data'][0]->values as $obj ) {
					$response['data'][] = array(
						'fans' => $obj->value,
						'time' => strtotime( $obj->end_time ),
						'date' => $obj->end_time
					);
				}
			}
			else {
				$response['error'] = array(
					'code' => 'STMPF',
					'type' => 'NotFoundError',
					'msg' => sprintf( lang('services_object_not_found'), 'Page Fans' )
				);
			}
		} catch ( \Exception $ex ) {
			$response['error'] = array(
				'code' => $ex->getCode(),
				'type' => ( method_exists($ex,'getErrorType') ? $ex->getErrorType() : 'Error' ),
				'msg' => $ex->getMessage()
			);
		}

		return $response;
	}

	public function service_test_measure_page_engaged_users( ) {

		$CI =& get_instance();
		$response = array( );
		$page = $CI->input->get('page');
		$period = $CI->input->get('period');

		if( !in_array( $period, array( 'day', 'week', 'days_28' ) ) )
			$period = 'day';

		$this->loadProperties();

		FacebookSession::setDefaultApplication( $this->getProperty('fb_insights_field_app_id'), $this->getProperty('fb_insights_field_app_secret') );
		// This will use de page's access_token coz it is a temporal access
		$session = new FacebookSession( $page['access_token'] );

		try {
			$request = new FacebookRequest($session, 'GET', '/' . $page['id'] . '/insights/page_engaged_users/' . $period, array( 'since' => strtotime( date("Y-m-d") . ' -1 month'), 'until' => strtotime( date( "Y-m-d") . " +1 days" ) ));
			$fbResponse = $request->execute()->getGraphObject()->asArray();

			if( !empty( $fbResponse['data'] ) ) {
				$obj = $fbResponse['data'][0]->values[0];
				$response['data'] = array(
					array(
						'engaged' => $obj->value,
						'time' => strtotime( $obj->end_time )
					)
				);
			}
			else {
				$response['error'] = array(
					'code' => 'STMPF',
					'type' => 'NotFoundError',
					'msg' => sprintf( lang('services_object_not_found'), 'USERS ENGAGED (' . $period . ')' )
				);
			}
		} catch ( \Exception $ex ) {
			$response['error'] = array(
				'code' => $ex->getCode(),
				'type' => ( method_exists($ex,'getErrorType') ? $ex->getErrorType() : 'Error' ),
				'msg' => $ex->getMessage()
			);
		}

		return $response;
	}

	public function getManageProjectView( ) {
		return 'manage/fb_project';
	}

	public function service_access( ) {

		$CI =& get_instance();
		$response = array( );
		$page = $CI->input->get('page');
		$user = $CI->input->get('user');
		$idSource = $CI->input->get('dataSource');
		$idProject = $CI->input->get('project');
		$authResponse = $CI->input->get('authResponse');

		if( !$authResponse
		    || !isset($authResponse['accessToken'])
			|| !$authResponse['accessToken']
			) {
			$response['error'] = array(
				'code' => 'SA11',
				'type' => 'MissingParamError',
				'msg' => sprintf( lang('services_missing_param'), 'accessToken' )
			);
		}
		else if( !$idSource || !$idProject ) {
			$response['error'] = array(
				'code' => 'SA12',
				'type' => 'MissingParamError',
				'msg' => sprintf( lang('services_missing_param'), 'ProjectSource' )
			);
		}
		else if( $idSource != $this->getId( ) ) {
			$response['error'] = array(
				'code' => 'SA12',
				'type' => 'SecurityError',
				'msg' => lang('services_access_denied')
			);
		}
		else {
			$this->loadProperties();

			FacebookSession::setDefaultApplication( $this->getProperty('fb_insights_field_app_id'), $this->getProperty('fb_insights_field_app_secret') );
			// This will use de user's access_token to create a long live one
			$session = new FacebookSession( $authResponse['accessToken'] );

			try {
				// User logged in, get the AccessToken entity.
				$accessToken = $session->getAccessToken( );
				// Exchange the short-lived token for a long-lived token.
				$longLivedAccessToken = $accessToken->extend( );
				/*
					// When using too much a long lived token
					// Get a code from a long-lived access token
					$code = AccessToken::getCodeFromAccessToken($longLivedAccessToken);
					// Get a new long-lived access token from the code
					$longLivedAccessToken = AccessToken::getAccessTokenFromCode($code);
				*/

				// This will use de user's access_token to create a long live one
				$session = new FacebookSession( $longLivedAccessToken );
				$request = new FacebookRequest( $session, 'GET', '/' . $page['id'] . '?fields=id,category,link,name,website,access_token,cover,about');
				$pageFound = $request->execute()->getGraphObject()->asArray();

				if( $pageFound && isset( $pageFound['access_token'] ) && $pageFound['access_token'] ) {
					$info = (new AccessToken( $pageFound['access_token'] ))->getInfo();
					if( !$info->getExpiresAt( ) ) {

						$properties = new ProjectProperties( $idSource, $idProject );
						$properties->set( array(
							'added' => 1,
							'id_page' => $pageFound['id'],
							'category' => $pageFound['category'],
							'link' => $pageFound['link'],
							'name' => $pageFound['name'],
							'website' => ( isset( $pageFound['website'] ) ? $pageFound['website'] : null ),
							'access_token' => $pageFound['access_token']
						));
						$properties->save();

						// Now store the page's perpetual-lived token in the database
						$response['data'] = array(
							array(
								'id' => $pageFound['id'],
								'name' => $pageFound['name'],
								'category' => ( isset( $pageFound['category'] ) ? $pageFound['category'] : null ),
								'about' => ( isset( $pageFound['about'] ) ? $pageFound['about'] : $pageFound['category'] ),
								'link' => ( isset( $pageFound['link'] ) ? $pageFound['link'] : null ),
								'website' => ( isset( $pageFound['website'] ) && strpos( $pageFound['website'], '<' ) === false ? $pageFound['website'] : null ),
								'cover' => ( isset( $pageFound['cover'] ) ? $pageFound['cover']->source : null )
							)
						);
					}
					else {
						$response['error'] = array(
							'code' => 'SA03',
							'type' => 'LiveAccessError',
							'msg' => lang('fb_services_short_token_live')
						);
					}
				}
				else {
					$response['error'] = array(
						'code' => 'SA02',
						'type' => 'NotFoundError',
						'msg' => sprintf( lang('services_object_not_found'), 'FACEBOOK PAGE' )
					);
				}
			} catch ( \Exception $ex ) {
				$response['error'] = array(
					'code' => $ex->getCode(),
					'type' => ( method_exists($ex,'getErrorType') ? $ex->getErrorType() : 'Error' ),
					'msg' => $ex->getMessage()
				);
			}
		}

		return $response;
	}

}