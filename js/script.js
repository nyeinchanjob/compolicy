var i = 0;
var MyApp = angular.module('MyApp', ['ngMaterial', 'ngMessages']);

MyApp.run(function ($rootScope) {
    $rootScope.$on('scope.stored', function (event, data) {
        console.log("scope.stored", data);
    });
});
MyApp.controller('AppCtrl', function($scope, $rootScope, Scopes, $mdSidenav) {
    // $scope.deals = [
    //   { product_id:i, product_buy:0, product_get:0, product_disc:0}
    //   ];
    Scopes.store('scriptScopes', $scope);
    $scope.deals = [
      { product_id:1, product_buy:120, product_get:1, product_disc:50},
      { product_id:2, product_buy:250, product_get:3, product_disc:1200},
      { product_id:3, product_buy:500, product_get:7, product_disc:10000}
      ];
    $scope.checked_index = [];
    $scope.checked_show = false;
    $scope.bundle_deals = [];
    $rootScope.loginCorrect = false;
    $scope.show_layout='survey_list';

    $scope.toggleLeft = buildToggler('left');

    $scope.logout = function () {
      $rootScope.loginCorrect = false;
      $mdSidenav('left').close()
    };

    function buildToggler(navID) {
      return function() {
        // Component lookup should always be available since we are not using `ng-if`
        $mdSidenav(navID).toggle();
      }
    }

    $scope.isOpenLeft = function(){
      return !$rootScope.loginCorrect;//$mdSidenav('left').isOpen();
    };

    $scope.add = function () {
      $scope.deals.push({product_id: i+1,product_buy:0, product_get:0, product_disc:0})
      i += 1;
    };

    $scope.selected_index = function (product) {
      if ($scope.checked_index.indexOf(product) === -1) {
           $scope.checked_index.push(product);
       }
       else {
           $scope.checked_index.splice($scope.checked_index.indexOf(product), 1);
       }
    };

    $scope.delete = function () {
      if ($scope.checked_index.length > 0) {
        for (var j = 0; j < $scope.checked_index.length; j++) {
          $scope.deals.splice($scope.deals.indexOf($scope.checked_index[j]),1);
        }

        if ($scope.deals.length == 0) {
          i = 0;
          $scope.deals = [
            { product_id:i, product_buy:0, product_get:0, product_disc:0}
            ];
          $scope.checked_index = [];
          $scope.checked_show = false;
        }
      }
    };

    $scope.showChecked = function () {
      $scope.checked_show = $scope.checked_show == false ? true : false;
    };

    $scope.reset = function () {
      i = 0;
      $scope.product_name = "";
      $scope.deals = [
        { product_id:i, product_buy:0, product_get:0, product_disc:0}
        ];
      $scope.checked_index = [];
      $scope.checked_show = false;
    };

    $scope.start_deals = function () {
      $scope.bundle_deals = [];
      $scope.deals = check_deals_zero($scope.deals);
      if ($scope.deals.length > 0) {
          $scope.bundle_deals = calculate_deals($scope.deals, parseInt($scope.deals[0]['product_buy']), parseInt($scope.deals[0]['product_get']), parseInt($scope.deals[0]['product_disc']));
      }
    };

    $scope.export = function() {
      var blob = new Blob([document.getElementById('tbl_quantites').innerHTML], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        $scope.product_name = String($scope.product_name).trim().length > 0 ? String($scope.product_name).trim().toUpperCase() : "";
        saveAs(blob, "Commercial_Policy_"+ $scope.product_name +".xls");
    };
  });

  MyApp.factory('Scopes', function ($rootScope) {
      var mem = {};

      return {
          store: function (key, value) {
              $rootScope.$emit('scope.stored', key);
              mem[key] = value;
          },
          get: function (key) {
              return mem[key];
          }
      };
  });

function check_deals_zero (deals) {
  bundle = [];
  for (var i = 0; i < deals.length; i++) {
    if (deals[i]['product_buy'] > 0 && deals[i]['product_get'] > 0) {
      bundle.push({
        'product_id': deals[i]['product_id'],
        'product_buy': deals[i]['product_buy'],
        'product_get': deals[i]['product_get'],
        'product_disc': deals[i]['product_disc'],
      })
    }
  }
  return bundle;
}

function calculate_deals (deals, deals_count,inc_qty, inc_disc) {
    bundle = [];
    di = 0;
    prod_i = 1;
    deals_qty = 0;
    deals_disc = 0;
    last_buy_count = 2;
    for (var i = deals_count; i < (deals[deals.length-1]['product_buy']*2) +1; i+=deals_count) {
      if (di <= deals.length -1) {
        if (i == parseInt(deals[di]['product_buy']) ) {
          deals_qty = parseInt(deals[di]['product_get']);
          deals_disc = parseInt(deals[di]['product_disc']);
          // di = (di < deals.length -1) ? di +=1 : deals.length -1;
          di +=1;
          bundle.push({'product_id': prod_i, 'min': i, 'max': (i + deals_count)-1, 'free': deals_qty, 'disc': deals_disc});
        } else {
          if (parseInt(i) == parseInt(deals[di-1]['product_buy'])*2) {
            deals_qty = parseInt(deals[di-1]['product_get'])*2;
            deals_disc = parseInt(deals[di-1]['product_disc'])*2;

            bundle.push({'product_id': prod_i, 'min': i, 'max': (i + deals_count)-1, 'free': deals_qty, 'disc': deals_disc});
          } else {
            deals_qty += inc_qty;
            deals_disc += inc_disc;
            bundle.push({'product_id': prod_i, 'min': i, 'max': (i + deals_count) -1, 'free': deals_qty, 'disc': deals_disc });
          }
        }
        if (di <= deals.length-1) {
          if (parseInt((i + deals_count) -1) > parseInt(deals[di]['product_buy'])) {
             bundle[bundle.length-1]['max'] =  parseInt(deals[di]['product_buy']) -1;
            i = parseInt(deals[di]['product_buy']) - parseInt(deals_count);
          }
        }
      } else {
        if (parseInt(i) == parseInt(deals[deals.length -1]['product_buy'])*last_buy_count) {
          deals_qty = parseInt(deals[deals.length -1]['product_get'])*last_buy_count;
          deals_disc = parseInt(deals[deals.length -1]['product_disc'])*last_buy_count;
          bundle.push({'product_id': prod_i, 'min': i, 'max': (i + deals_count)-1, 'free': deals_qty, 'disc': deals_disc});
        } else {
          deals_qty += inc_qty;
          deals_disc += inc_disc;
          bundle.push({'product_id': prod_i, 'min': i, 'max': (i + deals_count) -1, 'free': deals_qty, 'disc': deals_disc });
        }
      }
      prod_i +=1;
    }
    return bundle;
  };
