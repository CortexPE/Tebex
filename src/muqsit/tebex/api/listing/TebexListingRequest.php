<?php

declare(strict_types=1);

namespace muqsit\tebex\api\listing;

use muqsit\tebex\api\TebexGETRequest;
use muqsit\tebex\api\TebexResponse;
use muqsit\tebex\api\utils\TebexGUIItem;

/**
 * @phpstan-extends TebexGETRequest<TebexListingInfo>
 */
final class TebexListingRequest extends TebexGETRequest{

	public function getEndpoint() : string{
		return "/listing";
	}


	public function getExpectedResponseCode() : int{
		return 200;
	}

	/**
	 * @param array $response
	 * @return TebexResponse
	 *
	 * @phpstan-param array{
	 * 		categories: array<array{
	 * 			packages: array,
	 * 			subcategories: array<array{packages: array, id: int, order: int, name: string, gui_item: string|int}>,
	 * 			id: int,
	 * 			order: int,
	 * 			name: string,
	 * 			gui_item: string|int,
	 * 			only_subcategories: bool
	 * 		}>
	 * } $response
	 */
	public function createResponse(array $response) : TebexResponse{
		$categories = [];
		foreach($response["categories"] as $entry){
			$packages = [];
			foreach($entry["packages"] as $package){
				$packages[] = TebexPackage::fromTebexData($package);
			}

			$subcategories = [];
			foreach($entry["subcategories"] as $subcategory){
				$subcategory_packages = [];
				foreach($subcategory["packages"] as $package){
					$subcategory_packages[] = TebexPackage::fromTebexData($package);
				}

				$subcategories[] = new TebexSubCategory(
					$subcategory["id"],
					$subcategory["order"],
					$subcategory["name"],
					$subcategory_packages,
					new TebexGUIItem((string) $subcategory["gui_item"])
				);
			}

			$categories[] = new TebexCategory(
				$entry["id"],
				$entry["order"],
				$entry["name"],
				$packages,
				new TebexGUIItem((string) $entry["gui_item"]),
				$entry["only_subcategories"],
				$subcategories
			);
		}

		return new TebexListingInfo($categories);
	}
}