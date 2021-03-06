<?php
/**
 * @author Björn Schießle <schiessle@owncloud.com>
 *
 * @copyright Copyright (c) 2015, ownCloud, Inc.
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */


namespace OCA\Encryption\Controller;


use OCA\Encryption\Session;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\IL10N;
use OCP\IRequest;

class StatusController extends Controller {

	/** @var IL10N */
	private $l;

	/** @var Session */
	private $session;

	/**
	 * @param string $AppName
	 * @param IRequest $request
	 * @param IL10N $l10n
	 * @param Session $session
	 */
	public function __construct($AppName,
								IRequest $request,
								IL10N $l10n,
								Session $session
								) {
		parent::__construct($AppName, $request);
		$this->l = $l10n;
		$this->session = $session;
	}

	/**
	 * @NoAdminRequired
	 * @return DataResponse
	 */
	public function getStatus() {

		switch( $this->session->getStatus()) {
			case Session::INIT_EXECUTED:
				$status = 'success';
				$message = (string)$this->l->t(
					'Invalid private key for Encryption App. Please update your private'
					. ' key password in your personal settings to recover access to your'
					. ' encrypted files.', array('app' => 'encryption'));
				break;
			case Session::NOT_INITIALIZED:
				$status = 'success';
				$message = (string)$this->l->t(
					'Encryption App is enabled but your keys are not initialized,'
					. ' please log-out and log-in again', array('app' => 'encryption'));
				break;
			default:
				$status = 'error';
		}

		return new DataResponse(
			array(
				'status' => $status,
				'data' => array(
					'message' => $message)
			)
		);
	}

}
