//angular.module('MyProduct', ['ngMaterial', 'ngMessages'])
var MyApp = angular.module('MyApp');
MyApp.controller('ProductCtrl', ['$scope', '$mdDialog', '$http', function($scope, $mdDialog, $http) {
	$scope.cbValue = {};
    	$scope.cbValue.cbselect_all = false;
    	$scope.cbValue.cbselect_edit = false;
    	$scope.product_status = true;

    	$scope.showProductDetail = function() {
      		$mdDialog.show({
        		controller: DialogController,
        		templateUrl: 'templates/product/product_detail.html',
        		parent: angular.element(document.body),
        		clickOutsideToClose: true,
      		});
    	};

	$scope.clear = function() {
		$scope.product_id = undefined;
		$scope.product_code = undefined;
		$scope.product_name = undefined;
		$scope.product_status = true;
	}

	$scope.getAll = function() {
		$http.get('db_controller/product/read_product.php'
		).success( function (response) {
				$scope.items = response.records;
			});
	}


}]);

function DialogController($scope, $http, $mdDialog) {
	$scope.cancel = function() {
		$scope.product_id = undefined;
		$scope.product_code = undefined;
		$scope.product_name = undefined;
		$scope.product_status = true;

    	$mdDialog.cancel();
	};

  	$scope.create = function() {
		$http.post('db_controller/product/create_product.php', {
			'code' : $scope.product_code,
			'name' : $scope.product_name,
			'status' : $scope.product_status
		}).success(function(data, status, headers, config) {
   			$scope.product_id = undefined;
   			$scope.product_code = undefined;
   			$scope.product_name = undefined;
   			$scope.product_status = true;
			$scope.getAll();
		});
	}

	$scope.getAll = function() {
		console.log('Oops');
		$http.get('db_controller/product/read_product.php'
		).success( function (response) {
				$scope.items = response.records;
			});
	}

}

//function SearchProductList(searchWords) {
//  $http.post("db_controller/product.php?actionType=search&param=" + searchWords).success(function(data) {
//    console.log(data);
//  });
//}
