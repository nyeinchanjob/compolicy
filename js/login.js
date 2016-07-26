var MyApp = angular.module('MyApp');
MyApp.run(function ($rootScope) {
    $rootScope.$on('scope.stored', function (event, data) {
        console.log("scope.stored", data);
    });
});
MyApp.controller('LoginCtrl', ['$scope', '$http', '$rootScope', 'Scopes', function($scope, $http, $rootScope, Scopes){
  Scopes.store('loginScopes', $scope);
  $rootScope.loginCorrect = false;
  $scope.login = function() {
    $http.post('db_controller/login/check_login.php', {
      'username' : $scope.username,
      'password' : $scope.password
    }).success(function(data, status, headers, config) {
      $rootScope.loginCorrect = true;
      $scope.username = '';
      $scope.password = '';
      $scope.userInfo = {
        user_id : data[0]['user_id'],
        first_name : data[0]['firstname'],
        last_name : data[0]['lastname'],
        role_id : data[0]['role_id'],
        rolename : data[0]['rolename']
      };
    }).error(function(data, status, headers, config) {
      alert('Login found error. System can\'t login with this account. Please try again.');
    });
  };

}]);
