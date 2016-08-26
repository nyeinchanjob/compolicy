var MyApp = angular.module('MyApp');
MyApp.run(function ($rootScope) {
    $rootScope.$on('scope.stored', function (event, data) {
        console.log("scope.stored", data);
    });
});
MyApp.controller('LoginCtrl', ['$scope', '$http', '$rootScope', 'Scopes', '$mdToast', function($scope, $http, $rootScope, Scopes, $mdToast){
  Scopes.store('loginScopes', $scope);
  $rootScope.loginCorrect = false;
  $scope.login = function() {
    $http.post('db_controller/users/logtoin.php', {
      'username' : $scope.username,
      'password' : $scope.password
    }).success(function(data, status, headers, config) {
      if(Object.keys(data).length>0) {
        $scope.userInfo = {
          id : data['id'],
          name : data['name'],
          department : data['department'],
          position  : data['position']
        };
        $rootScope.loginCorrect = true;
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

}]);
