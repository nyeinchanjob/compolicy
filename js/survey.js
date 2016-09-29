//angular.module('MyProduct', ['ngMaterial', 'ngMessages'])

var MyApp = angular.module('MyApp');
MyApp.controller('SurveyCtrl', ['$scope', '$mdDialog', '$http', 'Upload', '$timeout', function(
	$scope, $mdDialog, $http, Upload, $timeout) {
	$scope.cbValue = {};
	$scope.cbValue.cbselect_all = false
	$scope.cbValue.cbselect_edit = false;
	$scope.show_detail = false;
	$scope.show_layout='survey_info';
	$scope.show_save = false;
	$scope.show_previous = false;
	$scope.show_next = true;
	$scope.outletInfo = {
		id : undefined,
		area : undefined,
		city_mm : undefined, city_en : undefined,
		township_mm :undefined, township_en : undefined,
		ward_mm : undefined, ward_en : undefined,
		outlet_type : undefined,
		outlet_mm : undefined, outlet_en : undefined,
		owner_mm : undefined, owner_en : undefined,
		phone1 :undefined, phone2 : undefined, phone3 : undefined,
		survey_status :undefined,
		longitude : undefined, latitude : undefined,
		image_path_1 : 'img/outlet.png', image_path_2 : 'img/outlet.png', image_path_3 : 'img/outlet.png',
		user_id : undefined
	};
	$scope.answers = [];
	$scope.other_size_radio = false;
	$scope.checkedByInserted = false;
	$scope.other_type_check = false;
	$scope.cbselected = [];
	$scope.action = 'new';
	$scope.detailTitle = '၏ အချက်အလက်များ';
	$scope.geo_path = 'img/maps.png';

	var file1;
	var file2;
	var file3;

	$scope.cancel = function() {
		$scope.outletInfo = {
        		id : undefined,
        		area : undefined,
        		city_mm : undefined, city_en : undefined,
        		township_mm :undefined, township_en : undefined,
        		ward_mm : undefined, ward_en : undefined,
        		outlet_type : undefined,
        		outlet_mm : undefined, outlet_en : undefined,
        		owner_mm : undefined, owner_en : undefined,
        		phone1 :undefined, phone2 : undefined, phone3 : undefined,
        		survey_status :undefined,
        		longitude : undefined, latitude : undefined,
        		image_path_1 : 'img/outlet.png', image_path_2 : 'img/outlet.png', image_path_3 : 'img/outlet.png',
        		user_id : undefined
		};
		$scope.answers = [];
		$scope.geo_path = 'img/maps.png';
		$scope.show_detail = false;
		$scope.action = 'new';
		file1 = undefined;
		file2 = undefined;
		file3 = undefined;
	};

	$scope.showProductDetail = function() {
		$scope.cancel();
		$scope.show_detail = true;
		$scope.action = 'new';
	};

	$scope.getAll = function() {
		$http.post('db_controller/surveys/read_survey.php').success(function(
			response) {
			$scope.items = response.records;
		});
	};


	$scope.readOne = function(id) {
		$scope.action = 'update';
		$scope.show_detail = true;
		$http.post('db_controller/surveys/read_one_survey.php', {
        		'id': id
        	}).success(function(data, status, headers, config) {
        		$scope.outletInfo = {
        			id : data[0]['id'],
        			area : data[0]['area'],
        			city_mm : data[0]['city_mm'], city_en : data[0]['city_en'],
        			township_mm : data[0]['township_mm'], township_en : data[0]['township_en'],
        			ward_mm : data[0]['ward_mm'], ward_en : data[0]['ward_en'],
        			outlet_type :data[0]['outlet_type'],
        			outlet_mm : data[0]['outlet_mm'], outlet_en : data[0]['outlet_en'],
        			owner_mm : data[0]['owner_mm'], owner_en : data[0]['owner_en'],
				phone1 : data[0]['phone1'], phone2 : data[0]['phone2'], phone3 : data[0]['phone3'],
				survey_status : data[0]['survey_status'] == '1' ? true : false,
        		longitude : data[0]['longitude'], latitude : data[0]['latitude'],
				image_path_1 : data[0]['image_path_1'], image_path_2 : data[0]['image_path_2'], image_path_3 : data[0]['image_path_3'],
				user_id : data[0]['user_id']
        		};

			$scope.geo_path = "https://maps.googleapis.com/maps/api/staticmap?center=" + $scope.outletInfo.latitude + "," +
                    			$scope.outletInfo.longitude + "&zoom=14&size=200x200&scale=2&maptype=roadmap&markers=color:red|" +
                    			$scope.outletInfo.latitude + ',' + $scope.outletInfo.longitude + "&key=AIzaSyBj6iTbEOvpPuxn8I4jjuRC2Oq4j6m16FU";


		}).error(function() {
        		$mdToast.show(
        			$mdToast.simple()
        				.textContent('Loading data caught Error. Please try again!')
        				.position('top left' )
        				.hideDelay(2000)
        			);
        	});
	};

	$scope.create = function() {
		if ($scope.outletInfo.outlet_mm != undefined && $scope.outletInfo.outlet_en !=	undefined &&
			$scope.answers.length != 0) {
			$http.post('db_controller/surveys/create_survey.php', {
				'area': $scope.outletInfo.area,
				'city_mm': $scope.outletInfo.city_mm, 'city_en' : $scope.outletInfo.city_en,
				'township_mm' : $scope.outletInfo.township_mm, 'township_en' : $scope.outletInfo.township_en,
				'ward_mm' :  $scope.outletInfo.ward_mm, 'ward_en' : $scope.outletInfo.ward_en,
				'outlet_type' : $scope.outletInfo.outlet_type,
				'outlet_mm': $scope.outletInfo.outlet_mm, 'outlet_en' : $scope.outletInfo.outlet_en,
				'owner_mm': $scope.outletInfo.owner_mm, 'owner_en' : $scope.outletInfo.owner_en,
				'phone1' : $scope.outletInfo.phone1, 'phone2' : $scope.outletInfo.phone2, 'phone3' : $scope.outletInfo.phone3,
				'survey_status': $scope.outletInfo.survey_status,
				'longitude' : $scope.outletInfo.longitude, 'latitude' : $scope.outletInfo.latitude,
				'image_path_1' : $scope.outletInfo.image_path_1, 'image_path_1' : $scope.outletInfo.image_path_2, 'image_path_3' : $scope.outletInfo.image_path_3,
				'user_id' : $scope.outletInfo.user_id,
				'answers' : $scope.answers
			}).success(function(data, status, headers, config) {
				$scope.cancel();
				$scope.getAll();
			});
		    var $f1 = file1;
		    var $f2 = file2;
		    var $f3 = file3;
		    Upload.upload({
			url : 'img/outlet/',
			file: $f1,
			progress : function(e){}
		    }).then(function(data, status, headers, config) {});

		    Upload.upload({
			url : 'img/outlet/',
			file : $f2,
			progress : function(e){}
		    }).then(function(data, status, headers, config){});

		    Upload.upload({
			url : 'img/outlet/',
			file: $f3,
			progress : function(e){}
		    }).then(function(data, status, headers, config){});
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
		if ($scope.outletInfo.outlet_mm != undefined && $scope.outletInfo.outlet_en != undefined &&
			$scope.answers.length != 0) {
			$http.post('db_controller/surveys/update_survey.php', {
				'id': $scope.outletInfo.id,
				'area': $scope.outletInfo.area,
        			'city_mm': $scope.outletInfo.city_mm, 'city_en' : $scope.outletInfo.city_en,
        			'township_mm' : $scope.outletInfo.township_mm, 'township_en' : $scope.outletInfo.township_en,
        			'ward_mm' :  $scope.outletInfo.ward_mm, 'ward_en' : $scope.outletInfo.ward_en,
        			'outlet_type' : $scope.outletInfo.outlet_type,
        			'outlet_mm': $scope.outletInfo.outlet_mm, 'outlet_en' : $scope.outletInfo.outlet_en,
        			'owner_mm': $scope.outletInfo.owner_mm, 'owner_en' : $scope.outletInfo.owner_en,
        			'phone1' : $scope.outletInfo.phone1, 'phone2' : $scope.outletInfo.phone2, 'phone3' : $scope.outletInfo.phone3,
        			'survey_status': $scope.outletInfo.survey_status,
        			'longitude' : $scope.outletInfo.longitude, 'latitude' : $scope.outletInfo.latitude,
        			'image_path_1' : $scope.outletInfo.image_path_1, 'image_path_1' : $scope.outletInfo.image_path_2, 'image_path_3' : $scope.outletInfo.image_path_3,
        			'user_id' : $scope.outletInfo.user_id,
        			'answers' : $scope.answers
			}).success(function(data, status, headers, config) {
				$scope.cancel();
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
 	$scope.delete = function() {
  		if (confirm("Are you sure want to delete?")) {
  			$http.post('db_controller/surveys/delete_survey.php', {
  				'id': $scope.cbselected
  			}).success(function(data, status, headers, config) {
  				$scope.getAll();
  			});
  		}
	};

	$scope.buttonAction = $scope.action == 'new' ? 'save' : 'update';

	$scope.changeActive = function(value) {
		$scope.outletInfo.survey_status = value;
	};

	$scope.AddToAnswer = function(item, list) {
		if (item.answer != 0 && item.answer != undefined) {
			for (var i = 0; i< list.length; i++) {
				var qas = list[i];
				if (qas.question_id == item.config_id) {
					list.splice(i, 1);
				}

			}
			var ans = {
					'question_id': item.config_id,
					'answer' : item.answer
			};
			list.push(ans);
		} else if (item.answer == 0 || item.answer == undefined) {
			var idx = list.indexOf(item.config_id);
			if (idx > -1) {
				list.splice(idx, 1);
			}
		}
		console.log(list);
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
		if ($scope.items != undefined) {
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
				$scope.cbselected.push($scope.items[i]['id']);
			}
		}
		$scope.cbValue.cbselect_edit = $scope.cbselected.length > 0 ? true :
			false;
	};

	$scope.next = function (layout) {
		if (layout == "survey_info") {
			$scope.show_layout = "survey_question";
			$scope.show_previous = true;
			$scope.show_save = false;
			$scope.detailTitle = '၏ အမေးအဖြေများ ';
		}
		if (layout == "survey_question") {
			$scope.show_layout = "survey_location";
			$scope.show_save = true;
			$scope.show_next = false;
			$scope.detailTitle = '၏ တည်နေရာ ';
		}
	};

	$scope.previous = function (layout) {
		if (layout == "survey_location") {
			$scope.show_layout = "survey_question";
			$scope.show_next = true;
			$scope.show_save = false;
			$scope.detailTitle = '၏ အမေးအဖြေများ ';
		}
		if (layout == "survey_question") {
			$scope.show_layout = "survey_info";
			$scope.show_save = false;
			$scope.show_previous = false;
			$scope.detailTitle = '၏ အချက်အလက်များ ';
		}
	};

	$scope.DetailClose = function() {
		$scope.show_detail = false;
	};

	$scope.initiate_geolocation = function() {
  		navigator.geolocation.getCurrentPosition($scope.handle_geolocation_query, $scope.handle_errors);
  	}

  	$scope.handle_geolocation_query = function(position) {

		$scope.outletInfo.latitude = position.coords.latitude;
    		$scope.outletInfo.longitude = position.coords.longitude;

		$scope.geo_path = "https://maps.googleapis.com/maps/api/staticmap?center=" + position.coords.latitude + "," +
                    position.coords.longitude + "&zoom=14&size=200x200&scale=2&maptype=roadmap&markers=color:red|" +
                    position.coords.latitude + ',' + position.coords.longitude + "&key=AIzaSyBj6iTbEOvpPuxn8I4jjuRC2Oq4j6m16FU"  ;
	}

	$scope.handle_errors = function(error) {
    		switch(error.code) {
      			case error.PERMISSION_DENIED:
				alert("user did not share geolocation data");
      				break;

      			case error.POSITION_UNAVAILABLE:
				alert("could not detect current position");
      				break;

      			case error.TIMEOUT:
				alert("retrieving position timed out");
      				break;

      			default:
				alert("unknown error");
      				break;
  		}
	}

	$scope.fileReaderSupported = window.FileReader != null;

	$scope.photoChanged = function(files, file_name){
		if (files != null) {
			switch(file_name) {
				case "file1":
					file1 = files[0];
					break;
				case "file2":
					file2 = files[0];
					break;
				case "file3":
					file3 = files[0];
					break;
			}
	  		var file = files[0];
	    		if ($scope.fileReaderSupported && file.type.indexOf('image') > -1) {
	      			$timeout(function() {
	        			var fileReader = new FileReader();
	          			fileReader.readAsDataURL(file);
	          			fileReader.onload = function(e) {
	          				$timeout(function(){
							switch(file_name) {
								case "file1":
	 								$scope.outletInfo.image_path_1 = e.target.result;
									console.log(file.name);
									break;
								case "file2":
									$scope.outletInfo.image_path_2 = e.target.result;
									break;
								case "file3":
									$scope.outletInfo.image_path_3 = e.target.result;
									break;
							}
	            				});
	          			}
	        		});
	      		}
	  	}
	};

	var formdata1 = new FormData();
	var formdata2 = new FormData();
	var formdata3 = new FormData();
	$scope.getTheFiles = function($files, file_name) {
		angular.forEach($files, function(value, key) {
			switch(file_name) {
				case "file1":
					formdata1.append(key, value);
					break;
				case "file2":
					formdata2.append(key, value);
					break;
				case "file3":
					formdata3.append(key, value);
					break;
			}
		});

	};

	$scope.uploadFile = function() {
		var request1 = {
			method : 'POST',
			url : 'img/fileUpload/',
			data : formdata1,
			headers : {
				'Content-Type' : undefined
			}
		};

		var request2 = {
        		method : 'POST',
        		url : 'img/fileUpload/',
        		data : formdata2,
        		headers : {
        			'Content-Type' : undefined
        		}
        	};

		var request3 = {
			method : 'POST',
			url : 'img/fileUpload/',
			data : formdata3,
			headers : {
				'Content-Type' : undefined
			}
		};

		$http(request1)
			.success(function (d) {})
			.error(function (){});

		$http(request2)
			.success(function (d) {})
			.error(function () {});

		$http(request3)
			.success(function (d) {})
			.error(function () {});
	};

	$scope.loadConfig = function() {
		$scope.cfgType = ['question', 'outlet_type'];
		$http.post('db_controller/surveys/read_survey_config.php', {
			'cType': $scope.cfgType
		}).success(function(response) {
			$scope.questions = response.records[0].questions;
			$scope.outlet_types = response.records[1].outlet_types;
		})
	};


}]);

MyApp.directive('ngFiles', ['$parse', function ($parse) {
	function fn_link (scope, element, attrs) {
		var onChange = $parse(attrs.ngFiles);
		element.on('change', function(event) {
			onChange(scope, { $files: event.target.files });
		});
	};

	return {
		link: fn_link
	}
}]);

MyApp.service('fileUpload', ['$http', function ($http) {
  this.uploadFileToUrl = function(file, uploadUrl){
     var fd = new FormData();
     fd.append('file', file);

     $http.post(uploadUrl, fd, {
        transformRequest: angular.identity,
        headers: {'Content-Type': undefined}
     })

     .success(function(){
     })

     .error(function(){
     });
  }
}]);
