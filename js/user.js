//angular.module('MyUser', ['ngMaterial', 'ngMessages'])
var MyApp = angular.module('MyApp');
MyApp.controller('UserCtrl', ['$scope', '$mdDialog', '$http', '$mdToast', function(
	$scope, $mdDialog, $http, $mdToast) {
	$scope.cbValue = {};
	$scope.cbValue.cbselect_all = false;
	$scope.cbValue.cbselect_edit = false;
	$scope.userInfo = {
		id: undefined,
		user_name: undefined,
		user_status: true
	}
	$scope.org_name = '';
	$scope.org_username = '';
	$scope.permissionSelected = {};
	$scope.cbselected = [];
	$scope.menu_arr = [];

	$scope.showUserDetail = function() {
		$mdDialog.show({
			controller: 'UserDialogCtrl',
			templateUrl: 'templates/users/user_detail.html',
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
		$http.get('db_controller/users/read_user.php').success(function(
			response) {
			$scope.items = response.records;
		});
	};



	$scope.readOne = function(id) {
		$mdDialog.show({
			controller: 'UserDialogCtrl',
			templateUrl: 'templates/users/user_detail.html',
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
			$http.post('db_controller/users/delete_user.php', {
				'id': $scope.cbselected
			}).success(function(data, status, headers, user) {
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

MyApp.controller('UserDialogCtrl',function($scope, $http, $mdDialog, $mdToast, id, action) {

	if (id != undefined) {
		$http.post('db_controller/users/read_role.php', {   	
        			
        		}).success(function(response) {
        			$scope.roles = response.records;
        		});


		$http.post('db_controller/users/read_one_user.php', {
			'id': id
		}).success(function(data, status, headers, user) {
			$scope.userInfo = {
				id : data[0]['id'],
				name : data[0]['name'],
				department : data[0]['department'],
				position : data[0]['position'],
				role_id : data[0]['role_id'],
				username : data[0]['username'],
				password : undefined,//data[0]['password'],
				confirmPassword : undefined,
				user_status: data[0]['user_status'] == '1' ? true : false
			};
			$scope.org_name = data[0]['name'];
			$scope.org_username = data[0]['username'];
		}).error(function(data, status, headers, user) {
			$mdToast.show(
				$mdToast.simple()
					.textContent('Loading data caught Error. Please try again!')
					.position('top left' )
					.hideDelay(2000)
				);
		});
	}

	$scope.loadConfig = function () {
		$http.post('db_controller/users/read_role.php', {   	
        			
        		}).success(function(response) {
        			$scope.roles = response.records;
        		});

	};

	$scope.buttonAction = action == 'new' ? 'save' : 'update';

	$scope.cancel = function() {
		$scope.userInfo = {
			id: undefined,
			name : undefined,
			department : undefined,
			position : undefined,
			role_id : undefined,
			username: undefined,
			password : undefined,
			confirmPassword : undefined,
			user_status: true
		}
		$mdDialog.cancel();
	};

	$scope.changeActive = function(value) {
		$scope.userInfo.user_status = value;
	};

	$scope.create = function() {
		if ($scope.userInfo.username != undefined && $scope.userInfo.name != undefined) {
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
		$http.post('db_controller/users/check_user.php', {
			'name': $scope.userInfo.name
		}).success(function(data, status, headers, user) {
			if (Object.keys(data).length == 0) {
				$http.post('db_controller/users/check_login.php', {
					'username' : $scope.userInfo.username
				}).success(function(data, status, headers, user) {
					if (Object.keys(data).length ==0) {
						$scope.save();
					} else {
						$mdToast.show(                                                                                      		
        						$mdToast.simple()
        							.textContent($scope.userInfo.username + ' being already used by  another user. System can not save.')
        							.position('top left' )
        							.hideDelay(2000)
						);                                                                                          	
					}
				});
			} else {
				$mdToast.show(
					$mdToast.simple()
						.textContent($scope.userInfo.user_name + ' is already Exists. System can not save.')
						.position('top left' )
						.hideDelay(2000)
					);
			}
		});
	};

	$scope.save = function() {
		$http.post('db_controller/users/create_user.php', {
			'name': $scope.userInfo.name,
			'department' : $scope.userInfo.department,
			'position' : $scope.userInfo.position,
			'role_id' : $scope.userInfo.role_id,
			'username' : $scope.userInfo.username,
			'password' : $scope.userInfo.password,
			'user_status': $scope.userInfo.user_status
		}).success(function(data, status, headers, user) {
			$scope.userInfo = {
				id: undefined,
				name: undefined,
				department : undefined,
				position : undefined,
				role_id : undefined,
				username : undefined,
				password : undefined,
				user_status: true
			};
			$scope.getAll();
			$mdDialog.cancel();
		});
	};

	$scope.update = function() {
		if ($scope.userInfo.name != undefined && $scope.userInfo.username != undefined) {
			if ($scope.org_name != $scope.userInfo.name && $scope.org_user_name != $scope.userInfo.username) {
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
		$http.post('db_controller/users/check_user.php', {
			'name': $scope.userInfo.name
		}).success(function(data, status, headers, user) {
			if (Object.keys(data).length == 0) {
				$http.post('db_controller/users/check_login.php', {
					'username' : $scope.userInfo.username 
				}).success(function(data, status, headers, user) {
					if (Object.keys(data).length == 0) {
						$scope.edit();
					} else {
					
					}
				});
			} else {
				$mdToast.show(
					$mdToast.simple()
						.textContent($scope.userInfo.user_name + ' is already Exists. System can not update.')
						.position('top left' )
						.hideDelay(2000)
					);
			}
		});
	}

	$scope.edit = function() {
		console.log($scope.permissionSelected);
		$http.post('db_controller/users/update_user.php', {
			'id': id,
			'name': $scope.userInfo.name,             		
        		'department' : $scope.userInfo.department,
        		'position' : $scope.userInfo.position,
        		'role_id' : $scope.userInfo.role_id,
        		'username' : $scope.userInfo.username,
        		'password' : $scope.userInfo.password,
			'user_status': $scope.userInfo.user_status	
		}).success(function(data, status, headers, user) {
			$scope.userInfo = {
				id: undefined,
				name: undefined,       			
        			department : undefined,
        			position : undefined,
        			role_id : undefined,
        			username : undefined,
        			password : undefined,
        			user_status: true
			};
			$scope.getAll();
			$mdDialog.cancel();
		});
	}

	$scope.getAll = function() {
		$http.get('db_controller/users/read_user.php').success(function(
			response) {
			$scope.items = response.records
		});
	};

	$scope.checkConfirmPassword = function() {
		if ($scope.userInfo.password == $scope.userInfo.confirmPassword) {
			return true;
		} else {
			return false;
		}
	}
});
