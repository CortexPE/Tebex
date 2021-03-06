<?php

declare(strict_types=1);

namespace muqsit\tebex\api\queue\commands\offline;

final class TebexQueuedOfflineCommandsMeta{

	/** @var bool */
	private $limited;

	public function __construct(bool $limited){
		$this->limited = $limited;
	}

	public function isLimited() : bool{
		return $this->limited;
	}
}