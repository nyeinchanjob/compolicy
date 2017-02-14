//angular.module('MyQuestionType', ['ngMaterial', 'ngMessages'])
var MyApp = angular.module('MyApp');
MyApp.controller('QuestionTypeCtrl', ['$scope', '$mdDialog', '$http', '$mdToast', function(
	$scope, $mdDialog, $http, $mdToast) {
	$scope.cbValue = {};
	$scope.cbValue.cbselect_all = false;
	$scope.cbValue.cbselect_edit = false;
	$scope.question_typeInfo = {
		id: undefined,
		question_type_name: undefined,
		question_type_status: true
	};
	$scope.org_name = '';
	$scope.permissionSelected = {};
	$scope.cbselected = [];
	$scope.menu_arr = [];

	$scope.showQuestionTypeDetail = function() {
		$mdDialog.show({
			controller: 'QuestionTypeDialogCtrl',
			templateUrl: 'templates/question_type/question_type_detail.html',
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
		$http.post('db_controller/question_type/read_question_type.php').success(function(
			response) {
			$scope.items = response.records;
		});
	};


	$scope.readOne = function(id) {
		$mdDialog.show({
			controller: 'QuestionTypeDialogCtrl',
			templateUrl: 'templates/question_type/question_type_detail.html',
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
			$http.post('db_controller/question_type/delete_question_type.php', {
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
	};

	$scope.exists = function(item, list) {
		if (list.length > 0) {
			return list.indexOf(item) > -1;
		}
	};

	$scope.isChecked = function() {
		if ($scope.items !== undefined) {
			return ($scope.cbselected.length === $scope.items.length);
		}
	};
	$scope.isIndeterminate = function() {
		return ($scope.cbselected.length !== 0 &&
			$scope.cbselected.length !== $scope.items.length);
	};
	$scope.selectAll = function() {
		if ($scope.cbselected.length === $scope.items.length) {
			$scope.cbselected = [];
		} else if ($scope.cbselected.length === 0 || $scope.cbselected.length > 0) {
			for (var i = 0; i < $scope.items.length; i++) {
				$scope.cbselected.push($scope.items[i].id);
			}
		}
		$scope.cbValue.cbselect_edit = $scope.cbselected.length > 0 ? true :
			false;
	};

}]);

MyApp.controller('QuestionTypeDialogCtrl',function($scope, $http, $mdDialog, $mdToast, id, action) {

	if (id !== undefined) {

		$http.post('db_controller/question_type/read_one_question_type.php', {
			'id': id
		}).success(function(data, status, headers, config) {
			$scope.question_typeInfo = {
				id : data[0].id,
				question_type_name : data[0].name,
				question_type_status: data[0].is_active == '1' ? true : false
			};
			$scope.org_name = data[0].name;
		}).error(function(data, status, headers, config) {
			$mdToast.show(
				$mdToast.simple()
					.textContent('Loading data caught Error. Please try again!')
					.position('top left' )
					.hideDelay(2000)
				);
		});
	}
	$scope.buttonAction = action == 'new' ? 'save' : 'update';

	$scope.cancel = function() {
		$scope.question_typeInfo = {
			id: undefined,
			question_type_name: undefined,
			question_type_status: true
		};
		$mdDialog.cancel();
	};

	$scope.changeActive = function(value) {
		$scope.question_typeInfo.question_type_status = value;
	};

	$scope.create = function() {
		if ($scope.question_typeInfo.question_type_name !== undefined) {
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
		$http.post('db_controller/question_type/check_question_type.php', {
			'name': $scope.question_typeInfo.question_type_name
		}).success(function(data, status, headers, config) {
			if (Object.keys(data).length === 0) {
				$scope.save();
			} else {
				$mdToast.show(
					$mdToast.simple()
						.textContent($scope.question_typeInfo.question_type_name + ' is already Exists. System can not save.')
						.position('top left' )
						.hideDelay(2000)
					);
			}
		});
	};

	$scope.save = function() {
		$http.post('db_controller/question_type/create_question_type.php', {
			'name': $scope.question_typeInfo.question_type_name,
			'is_active': $scope.question_typeInfo.question_type_status
		}).success(function(data, status, headers, config) {
			$scope.question_typeInfo = {
				id: undefined,
				question_type_name: undefined,
				question_type_status: true
			};
			$scope.getAll();
			$mdDialog.cancel();
		});
	};

	$scope.update = function() {
		if ($scope.question_typeInfo.question_type_name !== undefined) {
			if ($scope.org_name != $scope.question_typeInfo.question_type_name) {
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
		$http.post('db_controller/question_type/check_question_type.php', {
			'name': $scope.question_typeInfo.question_type_name
		}).success(function(data, status, headers, config) {
			if (Object.keys(data).length === 0) {
				$scope.edit();
			} else {
				$mdToast.show(
					$mdToast.simple()
						.textContent($scope.question_typeInfo.question_type_name + ' is already Exists. System can not update.')
						.position('top left' )
						.hideDelay(2000)
					);
			}
		});
	};

	$scope.edit = function() {
		console.log($scope.permissionSelected);
		$http.post('db_controller/question_type/update_question_type.php', {
			'id': id,
			'name': $scope.question_typeInfo.question_type_name,
			'is_active': $scope.question_typeInfo.question_type_status
		}).success(function(data, status, headers, config) {
			$scope.question_typeInfo = {
				id: undefined,
				question_type_name: undefined,
				question_type_status: true
			};
			$scope.getAll();
			$mdDialog.cancel();
		});
	};

	$scope.getAll = function() {
		$http.get('db_controller/question_type/read_question_type.php').success(function(
			response) {
			$scope.items = response.records;
		});
	};

});
