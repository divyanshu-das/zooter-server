<?php
App::uses('AppModel', 'Model');
/**
 * PlaceReview Model
 *
 * @property Place $Place
 * @property User $User
 */
class PlaceReview extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Place' => array(
			'className' => 'Place',
			'foreignKey' => 'place_id',
			'counterCache' => true,
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'counterCache' => 'review_count',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Image' => array(
			'className' => 'Image',
			'foreignKey' => 'image_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function getUserReviews($profileUserId) {
		$reviews = $this->find('all', array(
			'conditions' => array(
				'PlaceReview.user_id' => $profileUserId,
			),
			'fields' => array(
				'id', 'review'
			),
			'contain' => array(
				'Place' => array(
					'fields' => array('id', 'name'),
					'Location' => array(
						'fields' => array('id', 'name')
					),
					'PlacesImage' => array(
						'fields' => array('id'),
						'Image' => array(
							'fields' => array('id', 'url')
						)
					)
				)
			)
		));
		$data = array();
		if (!empty($reviews)) {
			$count = 0;
			foreach ($reviews as $id => $review) {
				$data[$count]['id'] = $review['Place']['id'];
				$data[$count]['name'] = $review['Place']['name'];
				$data[$count]['location'] = $review['Place']['Location']['name'];
				$data[$count]['review'] = $review['PlaceReview']['review'];
				$data[$count]['image_url'] = !empty($review['Place']['PlacesImage'][0]['Image']['url']) ? $review['Place']['PlacesImage'][0]['Image']['url'] : null;
				$count++;
			}
		}
		return $data;
	}

	public function addReview($placeId, $userId, $rating, $review, $image) {
		$data = array(
			'PlaceReview' => array(
				'place_id' => $placeId,
				'user_id' => $userId,
				'review' => $review
			)
		);
		if (!empty($image)) {
			$data['Image']['url'] = $image['url'];
			$data['Image']['user_id'] = $userId;
		}
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		if ($this->saveAssociated($data)) {
			$reviewId = $this->getLastInsertID();
			$rating = array(
				'place_id' => $placeId,
				'user_id' => $userId,
				'rating' => $rating
			);
			if ($this->Place->PlaceRating->saveRating($rating)) {
				$reviewData = $this->getReviewDataById($reviewId);
				$dataSource->commit();
				$response = array('status' => 200, 'message' => 'success', 'data' => $reviewData);
			} else {
				$dataSource->rollback();
				$response = array('status' => 400, 'message' => 'Could Not save Rating');

			}
		} else {
			$dataSource->rollback();
			$response = array('status' => 400, 'message' => 'Could Not save Review');
		}
		return $response;
	}

	public function getReviewDataById($reviewId) {
		$review = $this->find('first', array(
			'conditions' => array(
				'PlaceReview.id' => $reviewId
			),
			'fields' => array(
				'id', 'review', 'created', 'user_id'
			),
			'contain' => array(
				'User' => array(
					'fields' => array(
						'id', 'review_count'
					),
					'Profile' => array(
						'fields' => array('id', 'first_name'),
						'ProfileImage' => array(
							'fields' => array('id', 'url')
						)
					)
				),
				'Image' => array(
					'fields' => array('id', 'url')
				)
			)
		));
		App::uses('CakeTime', 'Utility');
		$data = array(
			'id' => $reviewId,
			'time' => CakeTime::timeAgoInWords($review['PlaceReview']['created']),
			'text' => $review['PlaceReview']['review'],
			'image' => !empty($review['Image']['url']) ? $review['Image']['url'] : null
		);
		if (!empty($review['User'])) {
			$data['user']['id'] = $review['User']['id'];
			$data['user']['name'] = $review['User']['Profile']['first_name'];
			$data['user']['url'] = !empty($review['User']['Profile']['ProfileImage']['url']) ? $review['User']['Profile']['ProfileImage']['url'] : null;
			$data['user']['review_count'] = $review['User']['review_count'];
		}
		return $data;
	}

}
