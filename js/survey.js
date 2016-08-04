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
		brand_id : undefined,
		size_id : undefined,
		other_size_detail : undefined,
		type_id : [],
		other_type_detail : undefined,
		product_status: true
	}
	$scope.other_size_radio = false;
	$scope.checkedByInserted = false;
	$scope.other_type_check = false;
	$scope.cbselected = [];

	$scope.showProductDetail = function() {
		$mdDialog.show({
			controller: DialogController,
			templateUrl: 'templates/surveys/survey_detail.html',
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
		$http.post('db_controller/surveys/read_survey.php').success(function(
			response) {
			$scope.items = response.records;
		});
	};


	$scope.readOne = function(id) {
		$mdDialog.show({
			controller: DialogController,
			templateUrl: 'templates/surveys/survey_detail.html',
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
			$http.post('db_controller/surveys/delete_survey.php', {
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

	$scope.loadConfig = function() {
		$scope.cfgType = ['brand', 'size', 'type'];
		$http.post('db_controller/surveys/read_survey_config.php', {
			'cType': $scope.cfgType
		}).success(function(response) {
			$scope.brands = response.records[0].brands;
			$scope.sizes = response.records[1].sizes;
			$scope.types = response.records[2].types;
			if (action == 'new') { $scope.productInfo.size_id = response.records[1].sizes[0]['config_id'];}

		})
	};

	if (id != undefined) {
		$http.post('db_controller/surveys/read_one_survey.php', {
			'id': id
		}).success(function(data, status, headers, config) {
			$scope.productInfo = {
				product_id : data[0]['id'],
				product_code : data[0]['code'],
				product_name : data[0]['name'],
				brand_id : data[0]['brandId'],
				size_id : data[0]['sizeId'],
				type_id :[],
				other_size_detail : data[0]['otherSizeDetail'],
				other_type_detail : data[0]['otherTypeDetail'],
				product_status: data[0]['status'] == '1' ? true : false
			};
			if (data[0]['otherSizeDetail'].length>0) { $scope.other_size_radio = true; }
			if (data[0]['otherTypeDetail'].length>0) { $scope.other_type_check = true; }

		}).error(function(data, status, headers, config) {
			$mdToast.show(
				$mdToast.simple()
					.textContent('Loading data caught Error. Please try again!')
					.position('top left' )
					.hideDelay(2000)
				);
		});

		$http.post('db_controller/survyes/read_type_survey.php', {
			'id' : id
		}).success(function(data, status, headers, config) {
			$scope.productInfo.type_id = data[0]['typeId'];

		}).error(function(data, status, headers, config) {
			$mdToast.show(
				$mdToast.simple()
					.textContent('Loading data caught on Type Error. Please try again!')
					.position('top left' )
					.hideDelay(2000)
				);
		});

	}

	$scope.hideOtherRadio = function(item) {
				$scope.other_size_radio = item.config_code == 'oth' ? true : false;
	};

	$scope.checkedByInserted = function(id) {
		if ($scope.productInfo.type_id.length>0) {
			var checked = false;
			for(var i = 0; i< $scope.productInfo.type_id.length; i++) {
				if (id == $scope.productInfo.type_id[i]) {
					checked = true;
				}
			}
		}
		return checked;
	}

	$scope.buttonAction = action == 'new' ? 'save' : 'update';

	$scope.cancel = function() {
		$scope.productInfo = {
			product_id: undefined,
			product_code: undefined,
			product_name: undefined,
			brand_id : undefined,
			size_id : undefined,
			other_size_detail : undefined,
			type_id : [],
			other_type_detail : undefined,
			product_status: true
		}
		$mdDialog.cancel();
	};

	$scope.changeActive = function(value) {
		$scope.productInfo.product_status = value;
	};

	$scope.checkedType = function(item, list) {
		var idx = list.indexOf(item.config_id);
		if (idx > -1) {
			list.splice(idx, 1);
		} else {
			list.push(item.config_id);
		}
		if (item.config_code == 'oth') {
			$scope.other_type_check =  !$scope.other_type_check;
		}
	};

	$scope.create = function() {
		console.log($scope.productInfo.other_type_checked);
		if ($scope.productInfo.product_code != undefined && $scope.productInfo.product_name !=
			undefined &&
			$scope.productInfo.product_status != undefined) {
			$http.post('db_controller/surveys/create_survey.php', {
				'code': $scope.productInfo.product_code,
				'name': $scope.productInfo.product_name,
				'brandId' : $scope.productInfo.brand_id,
				'sizeId' :  $scope.productInfo.size_id,
				'typeId' : $scope.productInfo.type_id,
				'sizeOtherDetail': $scope.productInfo.other_size_detail,
				'typeOtherDetail': $scope.productInfo.other_type_detail,
				'status': $scope.productInfo.product_status
			}).success(function(data, status, headers, config) {
				$scope.productInfo = {
					product_id: undefined,
					product_code: undefined,
					product_name: undefined,
					brand_id : undefined,
					size_id : undefined,
					other_size_detail : undefined,
					type_id : [],
					other_type_detail : undefined,
					product_status: true
				};
				$scope.getAll();
			});
		} else {
			$mdToast.show(
				$mdToast.simple()
					.textContent('Data are undefined. System can not save.')
					.position('top left' )
					.hideDelay(2000)
				);
		}
	};

	$scope.update = function() {
		if ($scope.productInfo.product_code != undefined && $scope.productInfo.product_name !=
			undefined &&
			$scope.productInfo.product_status != undefined) {
			$http.post('db_controller/surveys/update_survey.php', {
				'id': id,
				'code': $scope.productInfo.product_code,
				'name': $scope.productInfo.product_name,
				'brandId' : $scope.productInfo.brand_id,
				'sizeId' : $scope.productInfo.size_id,
				'typeId' : $scope.productInfo.type_id,
				'sizeOtherDetail': $scope.productInfo.other_size_detail,
				'typeOtherDetail': $scope.productInfo.other_type_detail,
				'status': $scope.productInfo.product_status
			}).success(function(data, status, headers, config) {
				$scope.productInfo = {
					product_id: undefined,
					product_code: undefined,
					product_name: undefined,
					brand_id : undefined,
					size_id : undefined,
					other_size_detail : undefined,
					type_id : [],
					other_type_detail : undefined,
					product_status: true
				};
				$scope.getAll();
			});
		} else {
			$mdToast.show(
				$mdToast.simple()
					.textContent('Data are undefined. System can not update.')
					.position('top left' )
					.hideDelay(2000)
				);
		}
	};

	$scope.getAll = function() {
		$http.get('db_controller/surveys/read_survey.php').success(function(
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
