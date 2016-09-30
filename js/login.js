var MyApp = angular.module('MyApp');
MyApp.run(function ($rootScope) {
	$rootScope.$on('scope.stored', function (event, data) {});
});

MyApp.controller('LoginCtrl', ['$scope', '$http', '$rootScope', 'Scopes',
	'$mdToast', '$state', '$cookies', function($scope, $http, $rootScope, Scopes, $mdToast, $state, $cookies){
		Scopes.store('loginScopes', $scope);
		$rootScope.loginCorrect = false;
		$scope.login = function() {
			$http.post('db_controller/users/logtoin.php', {
				'username' : $scope.username,
				'password' : $scope.password
		}).success(function(data, status, headers, config) {
			if(Object.keys(data).length>0 && data['id'] != null) {
				$scope.userInfo = {
					id : data['id'],
					name : data['name'],
					department : data['department'],
					position  : data['position'],
					role_id : data['role_id'],
					role_name : data['role_name']
				};
				$rootScope.user_id = $scope.userInfo.id;
				$rootScope.user_name = $scope.userInfo.name;
				$rootScope.role_id = $scope.userInfo.role_id;
				$rootScope.role_name = $scope.userInfo.role_name;
				// $scope.getAllUser($rootScope.role_id);
				$rootScope.loginCorrect = true;
				$cookies.remove('loginCorrect');
				$cookies.remove('user_id');
				$cookies.remove('role_name');
				$cookies.remove('user_name');
				$cookies.put('loginCorrect', true);
				$cookies.put('user_id', $scope.userInfo.id);
				$cookies.put('role_name', $scope.userInfo.role_name);
				$cookies.put('user_name', $scope.userInfo.name);
				$state.go('home');
			} else {
				$mdToast.show(
					$mdToast.simple()
						.textContent('Username and Password are incorrect. Please try again.')
						.position('top right' )
						.hideDelay(2000)
				);
				$rootScope.loginCorrect = false;
			}
			$scope.username = '';
			$scope.password = '';

		}).error(function(data, status, headers, config) {
			$mdToast.show(
				$mdToast.simple()
					.textContent('Login has found error. Please contact your IT Administrator.')
					.position('top right' )
					.hideDelay(2000)
			);
			$rootScope.loginCorrect = false;
		});
	};

	// $scope.getAllUser = function(role_id) {
	// 	$http.post('db_controller/users/read_user.php', {
	// 		'issysadmin' : role_id == '1' ? true : false
	// 	}).success(function(response) {
	// 		$rootScope.userItems = response.records;
	// 	});
	//
	// 	$http.post('db_controller/surveys/read_survey.php',{
	// 		'user_id' : $scope.userInfo['id']
	// 	}).success(function(response) {
	// 		$rootScope.surveyItems = response.records;
	// 	});
	// };

}]);
