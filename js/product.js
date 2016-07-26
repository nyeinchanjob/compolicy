//angular.module('MyProduct', ['ngMaterial', 'ngMessages'])

var MyApp = angular.module('MyApp');
MyApp.controller('ProductCtrl', ['$scope', '$mdDialog', '$http', function(
	$scope, $mdDialog, $http) {
	$scope.cbValue = {};
	$scope.cbValue.cbselect_all = false;
	$scope.cbValue.cbselect_edit = false;
	$scope.productInfo = {
		product_id: undefined,
		product_code: undefined,
		product_name: undefined,
		product_status: true
	}

	$scope.typechecked = false;
	$scope.cbselected = [];

	$scope.showProductDetail = function() {
		$mdDialog.show({
			controller: DialogController,
			templateUrl: 'templates/product/product_detail.html',
			locals: {
				id: undefined,
				action: 'new'
			},
			scope: $scope,
			preserveScope: true,
			parent: angular.element(document.body),
			clickOutsideToClose: false,
		});
	};

	$scope.getAll = function() {
		$http.post('db_controller/product/read_product.php').success(function(
			response) {
			$scope.items = response.records;
		});
	};


	$scope.readOne = function(id) {
		$mdDialog.show({
			controller: DialogController,
			templateUrl: 'templates/product/product_detail.html',
			locals: {
				id: id,
				action: 'update'
			},
			scope: $scope,
			preserveScope: true,
			parent: angular.element(document.body),
			clickOutsideToClose: false,
		});
	};

	$scope.delete = function() {
		if (confirm("Are you sure want to delete?")) {
			$http.post('db_controller/product/delete_product.php', {
				'id': $scope.cbselected
			}).success(function(data, status, headers, config) {
				$scope.getAll();
			});
		}
	};

	$scope.checkedToEdit = function(item, list) {
		var idx = list.indexOf(item);
		if (idx > -1) {
			list.splice(idx, 1);
		} else {
			list.push(item);
		}
		$scope.cbValue.cbselect_edit = list.length > 0 ? true : false;
	}

	$scope.exists = function(item, list) {
		if (list.length > 0) {
			return list.indexOf(item) > -1;
		}
	}

	$scope.isChecked = function() {
		if ($scope.items != undefined) {
			return ($scope.cbselected.length === $scope.items.length);
		}
	}
	$scope.isIndeterminate = function() {
		return ($scope.cbselected.length !== 0 &&
			$scope.cbselected.length !== $scope.items.length);
	}
	$scope.selectAll = function() {
		if ($scope.cbselected.length === $scope.items.length) {
			$scope.cbselected = [];
		} else if ($scope.cbselected.length === 0 || $scope.cbselected.length > 0) {
			for (var i = 0; i < $scope.items.length; i++) {
				$scope.cbselected.push($scope.items[i]['product_id']);
			}
		}
		$scope.cbValue.cbselect_edit = $scope.cbselected.length > 0 ? true :
			false;
	}



}]);

function DialogController($scope, $http, $mdDialog, id, action) {
	if (id != undefined) {
		$http.post('db_controller/product/read_one_product.php', {
			'id': id
		}).success(function(data, status, headers, config) {
			$scope.productInfo = {
				product_id: data[0]['id'],
				product_code: data[0]['code'],
				product_name: data[0]['name'],
				product_status: data[0]['status'] == '1' ? true : false
			};
		}).error(function(data, status, headers, config) {
			alert('Loading caught Error. Please try again!')
		});
	}

	$scope.loadConfig = function() {
		$scope.cfgType = ['brand', 'size', 'type'];
		$http.post('db_controller/product/read_product_config.php', {
			'cType': $scope.cfgType
		}).success(function(response) {
			console.log(response.records);
			$scope.brands = response.records[0];
			$scope.sizes = response.records[1];
			$scope.types = response.records[2];
		})
	};

	$scope.buttonAction = action == 'new' ? 'save' : 'update';

	$scope.cancel = function() {
		$scope.productInfo = {
			product_id: undefined,
			product_code: undefined,
			product_name: undefined,
			product_status: true
		}
		$mdDialog.cancel();
	};

	$scope.changeActive = function(value) {
		$scope.productInfo.product_status = value;
	};

	$scope.create = function() {
		if ($scope.productInfo.product_code != undefined && $scope.productInfo.product_name !=
			undefined &&
			$scope.productInfo.product_status != undefined) {
			$http.post('db_controller/product/create_product.php', {
				'code': $scope.productInfo.product_code,
				'name': $scope.productInfo.product_name,
				'status': $scope.productInfo.product_status
			}).success(function(data, status, headers, config) {
				$scope.productInfo = {
					product_id: undefined,
					product_code: undefined,
					product_name: undefined,
					product_status: true
				};
				$scope.getAll();
			});
		} else {
			alert('Data are undefined. Can\'t  continue.');
		}
	};

	$scope.update = function() {
		if ($scope.productInfo.product_code != undefined && $scope.productInfo.product_name !=
			undefined &&
			$scope.productInfo.product_status != undefined) {
			$http.post('db_controller/product/update_product.php', {
				'id': id,
				'code': $scope.productInfo.product_code,
				'name': $scope.productInfo.product_name,
				'status': $scope.productInfo.product_status
			}).success(function(data, status, headers, config) {
				$scope.productInfo = {
					product_id: undefined,
					product_code: undefined,
					product_name: undefined,
					product_status: true
				};
				$scope.getAll();
			});
		} else {
			alert('Data are undefined. Can\'t  continue.');
		}
	};

	$scope.getAll = function() {
		$http.get('db_controller/product/read_product.php').success(function(
			response) {
			$scope.items = response.records;
		});
	};

}

//function SearchProductList(searchWords) {
//  $http.post("db_controller/product.php?actionType=search&param=" + searchWords).success(function(data) {
//    console.log(data);
//  });
//}


// a = {'records': [
// 		{'brand':[
// 			{'id':1, 'config_code': 'CCL', 'config_value': 'Coca-Cola', 'config_type': 'brand', 'config_status':1},
// 			{'id':2, 'config_code': 'MAX', 'config_value': 'Max', 'config_type': 'brand', 'config_status':1},
// 			{'id':3, 'config_code': 'BRN', 'config_value': 'Burn', 'config_type': 'brand', 'config_status':1}
// 		]},
// 		{'size':[
// 			{'id':4, 'config_code': '1L', 'config_value': '1 Liter', 'config_type': 'size', 'config_status':1},
// 			{'id':5, 'config_code': '1_5L', 'config_value': '1.5 Liter', 'config_type': 'size', 'config_status':1},
// 			{'id':6, 'config_code': '330ML', 'config_value': '330 ML', 'config_type': 'size', 'config_status':1}
// 		]},
// 		{'type':[
// 			{'id':7, 'config_code': 'PET', 'config_value': 'PET', 'config_type': 'type', 'config_status':1},
// 			{'id':8, 'config_code': 'RGB', 'config_value': 'RGB', 'config_type': 'type', 'config_status':1},
// 			{'id':9, 'config_code': 'CAN', 'config_value': 'CAN', 'config_type': 'type', 'config_status':1}
// 		]}
// 	]}
