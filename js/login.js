var MyApp = angular.module('MyApp');
MyApp.controller('LoginCtrl', ['$scope', '$http', function($scope, $http){

  $scope.login = function() {
    $http.post('db_contorller/login/check_login.php', {
      'username' : $scope.username,
      'password' : $scope.password
    }).success(function(data, status, headers, config) {
      $scope.loginCorrect = true;
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
