//angular.module('MyConfig', ['ngMaterial', 'ngMessages'])
var MyApp = angular.module('MyApp');
MyApp.controller('ConfigCtrl', ['$scope', '$mdDialog', '$http', '$mdToast', function(
	$scope, $mdDialog, $http, $mdToast) {
	$scope.cbValue = {};
	$scope.cbValue.cbselect_all = false;
	$scope.cbValue.cbselect_edit = false;
	$scope.configInfo = {
		id: undefined,
		config_value: undefined,
		config_type: undefined,
		config_status: true
	}
	$scope.org_value = '';
	$scope.org_type = '';
	$scope.cbselected = [];

	$scope.showConfigDetail = function() {
		$mdDialog.show({
			controller: 'ConfigDialogCtrl',
			templateUrl: 'templates/configs/config_detail.html',
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
		$http.post('db_controller/configs/read_config.php').success(function(
			response) {
			$scope.groups = response.records;
		});
	};


	$scope.readOne = function(id) {
		$mdDialog.show({
			controller: 'ConfigDialogCtrl',
			templateUrl: 'templates/configs/config_detail.html',
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
			$http.post('db_controller/configs/delete_config.php', {
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
				$scope.cbselected.push($scope.items[i]['id']);
			}
		}
		$scope.cbValue.cbselect_edit = $scope.cbselected.length > 0 ? true :
			false;
	}

}]);

MyApp.controller('ConfigDialogCtrl',function($scope, $http, $mdDialog, $mdToast, id, action) {

	if (id != undefined) {
		$http.post('db_controller/configs/read_one_config.php', {
			'id': id
		}).success(function(data, status, headers, config) {
			$scope.configInfo = {
				id : data[0]['id'],
				config_value : data[0]['value'],
				config_type : data[0]['type'],
				config_status: data[0]['status'] == '1' ? true : false
			};
			$scope.org_value = data[0]['value'];
			$scope.org_type = data[0]['type'];
		}).error(function(data, status, headers, config) {
			$mdToast.show(
				$mdToast.simple()
					.textContent('Loading data caught Error. Please try again!')
					.position('top left' )
					.hideDelay(2000)
				);
		});
	};

	$scope.buttonAction = action == 'new' ? 'save' : 'update';

	$scope.cancel = function() {
		$scope.configInfo = {
			id: undefined,
			config_vale: undefined,
			config_type: undefined,
			config_status: true
		}
		$mdDialog.cancel();
	};

	$scope.changeActive = function(value) {
		$scope.configInfo.config_status = value;
	};

	$scope.create = function() {
		if ($scope.configInfo.config_value != undefined) {
			$scope.checkAndSave();
		} else {
			$mdToast.show(
				$mdToast.simple()
					.textContent('Data are undefined. System can not save.')
					.position('top left' )
					.hideDelay(2000)
				);
		}
	};

	$scope.checkAndSave = function() {
		$http.post('db_controller/configs/check_config.php', {
			'value': $scope.configInfo.config_value,
			'type': $scope.configInfo.config_type
		}).success(function(data, status, headers, config) {
			if (Object.keys(data).length == 0) {
				$scope.save();
			} else {
				$mdToast.show(
					$mdToast.simple()
						.textContent($scope.configInfo.config_value + ' is already Exists in '+ $scope.configInfo.config_type +' type. System can not save.')
						.position('top left' )
						.hideDelay(2000)
					);
			}
		});
	};

	$scope.save = function() {
		$http.post('db_controller/configs/create_config.php', {
			'value': $scope.configInfo.config_value,
			'type': $scope.configInfo.config_type,
			'status': $scope.configInfo.config_status
		}).success(function(data, status, headers, config) {
			$scope.configInfo = {
				id: undefined,
				config_value: undefined,
				config_type: undefined,
				config_status: true
			};
			$scope.getAll();
			$mdDialog.cancel();
		});
	};

	$scope.update = function() {
		if ($scope.configInfo.config_value != undefined) {
			if ($scope.org_value != $scope.configInfo.config_value) {
					$scope.checkAndUpdate();
			} else {
				$scope.edit();
			}
		} else {
			$mdToast.show(
				$mdToast.simple()
					.textContent('Data are undefined. System can not update.')
					.position('top left' )
					.hideDelay(2000)
				);
		}
	};

	$scope.checkAndUpdate = function() {
		$http.post('db_controller/configs/check_config.php', {
			'value': $scope.configInfo.config_value,
			'type': $scope.configInfo.config_type,
		}).success(function(data, status, headers, config) {
			if (Object.keys(data).length == 0) {
				$scope.edit();
			} else {
				$mdToast.show(
					$mdToast.simple()
						.textContent($scope.configInfo.config_value + ' is already Exists in '+ $scope.configInfo.config_type +' type. System can not save.')
						.position('top left' )
						.hideDelay(2000)
					);
			}
		});
	}

	$scope.edit = function() {
		$http.post('db_controller/configs/update_config.php', {
			'id': id,
			'value': $scope.configInfo.config_value,
			'type': $scope.configInfo.config_type,
			'status': $scope.configInfo.config_status
		}).success(function(data, status, headers, config) {
			$scope.configInfo = {
				id: undefined,
				config_value: undefined,
				config_type: undefined,
				config_status: true
			};
			$scope.getAll();
			$mdDialog.cancel();
		});
	}

	$scope.getAll = function() {
		$http.get('db_controller/configs/read_config.php').success(function(
			response) {
			$scope.groups = response.records;
		});
	};

});
