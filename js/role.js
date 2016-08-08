//angular.module('MyRole', ['ngMaterial', 'ngMessages'])
var MyApp = angular.module('MyApp');
MyApp.controller('RoleCtrl', ['$scope', '$mdDialog', '$http', '$mdToast', function(
	$scope, $mdDialog, $http, $mdToast) {
	$scope.cbValue = {};
	$scope.cbValue.cbselect_all = false;
	$scope.cbValue.cbselect_edit = false;
	$scope.roleInfo = {
		id: undefined,
		role_name: undefined,
		role_status: true
	}
	$scope.org_name = '';
	$scope.cbselected = [];

	$scope.showRoleDetail = function() {
		$mdDialog.show({
			controller: 'DialogCtrl',
			templateUrl: 'templates/roles/role_detail.html',
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
		$http.post('db_controller/roles/read_role.php').success(function(
			response) {
			$scope.items = response.records;
		});
	};


	$scope.readOne = function(id) {
		$mdDialog.show({
			controller: 'DialogCtrl',
			templateUrl: 'templates/roles/role_detail.html',
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
			$http.post('db_controller/roles/delete_role.php', {
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

MyApp.controller('DialogCtrl',function($scope, $http, $mdDialog, $mdToast, id, action) {

	if (id != undefined) {
		$http.post('db_controller/roles/read_one_role.php', {
			'id': id
		}).success(function(data, status, headers, config) {
			$scope.roleInfo = {
				id : data[0]['id'],
				role_name : data[0]['name'],
				role_status: data[0]['status'] == '1' ? true : false
			};
			$scope.org_name = data[0]['name'];
		}).error(function(data, status, headers, config) {
			$mdToast.show(
				$mdToast.simple()
					.textContent('Loading data caught Error. Please try again!')
					.position('top left' )
					.hideDelay(2000)
				);
		});
	}
	$scope.loadConfig = function () {

		$http.post('db_controller/roles/read_config.php', {
			'config_type': ['menu', 'control']
		}).success(function(response) {
			console.log(response.records[0]);
			$scope.items = response.records[0]['data'];
			$scope.controls = response.records[1]['data'];
		});
	};

	$scope.buttonAction = action == 'new' ? 'save' : 'update';

	$scope.cancel = function() {
		$scope.roleInfo = {
			id: undefined,
			role_name: undefined,
			role_status: true
		}
		$mdDialog.cancel();
	};

	$scope.changeActive = function(value) {
		$scope.roleInfo.role_status = value;
	};

	$scope.create = function() {
		if ($scope.roleInfo.role_name != undefined) {
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
		$http.post('db_controller/roles/check_role.php', {
			'name': $scope.roleInfo.role_name
		}).success(function(data, status, headers, config) {
			if (Object.keys(data).length == 0) {
				$scope.save();
			} else {
				$mdToast.show(
					$mdToast.simple()
						.textContent($scope.roleInfo.role_name + ' is already Exists. System can not save.')
						.position('top left' )
						.hideDelay(2000)
					);
			}
		});
	};

	$scope.save = function() {
		$http.post('db_controller/roles/create_role.php', {
			'name': $scope.roleInfo.role_name,
			'status': $scope.roleInfo.role_status
		}).success(function(data, status, headers, config) {
			$scope.roleInfo = {
				id: undefined,
				role_name: undefined,
				role_status: true
			};
			$scope.getAll();
			$mdDialog.cancel();
		});
	};

	$scope.update = function() {
		if ($scope.roleInfo.role_name != undefined) {
			if ($scope.org_name != $scope.roleInfo.role_name) {
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
		$http.post('db_controller/roles/check_role.php', {
			'name': $scope.roleInfo.role_name
		}).success(function(data, status, headers, config) {
			if (Object.keys(data).length == 0) {
				$scope.edit();
			} else {
				$mdToast.show(
					$mdToast.simple()
						.textContent($scope.roleInfo.role_name + ' is already Exists. System can not update.')
						.position('top left' )
						.hideDelay(2000)
					);
			}
		});
	}

	$scope.edit = function() {
		$http.post('db_controller/roles/update_role.php', {
			'id': id,
			'name': $scope.roleInfo.role_name,
			'status': $scope.roleInfo.role_status
		}).success(function(data, status, headers, config) {
			$scope.roleInfo = {
				id: undefined,
				role_name: undefined,
				role_status: true
			};
			$scope.getAll();
			$mdDialog.cancel();
		});
	}

	$scope.getAll = function() {
		$http.get('db_controller/roles/read_role.php').success(function(
			response) {
			$scope.items = response.records;
		});
	};

});
