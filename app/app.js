'use strict';
var api_url = "http://localhost/angular_test/api/";
var request_header = {'Authorization': 'Basic ' + window.btoa(unescape(encodeURIComponent('admin:1234')))};
// Declare app level module which depends on views, and components
angular.module('myApp', [
    'ngRoute',
    'base64'
]).
    config(['$locationProvider', '$routeProvider', function ($locationProvider, $routeProvider) {
        //$locationProvider.hashPrefix('!');
        $routeProvider.
        when('/contacts', {
            templateUrl: 'contact_list.html',
            controller: 'ContactListCtrl'
        }).
        when('/contacts_form/:contact_id?', {
            templateUrl: 'contact_form.html',
            controller: 'ContactFormCtrl'
        }).
        otherwise({
            redirectTo: '/contacts'
        });
    }])
    .controller('ContactListCtrl', function ($scope, $http, $location) {
        $http({
            url: api_url + "contact/all/format/json",
            method: "GET",
            headers: request_header,
        }).then(function successCallback(resp) {
            if (resp.data.success) {
                $scope.contacts = resp.data.data;
            } else {
                alert("Can't fetch contacts list");
            }
        }, function errorCallback(resp) {
            console.log(resp);
        });

        $scope.delete = function (contact_id) {
            $http({
                url: api_url + "contact/delete",
                method: "POST",
                data: {id: contact_id},
                headers: request_header,
            }).then(function successCallback(resp) {
                $location.path("#contact_list");
            }, function errorCallback(resp) {
                console.log(resp);
            });
        };

    })
    .controller('ContactFormCtrl', function ($scope, $http, $location, $routeParams, $base64) {
        $scope.contactId = $routeParams.contact_id;
        $scope.pageTitle = ($scope.contactId) ? "Edit Contact" : "Add Contact";
        $scope.contactinfo = {};

        if ($scope.contactId) {
            $http({
                url: api_url + "contact/index/id/" + $scope.contactId + "/format/json",
                method: "GET",
                headers: request_header,
            }).then(function successCallback(resp) {
                if (resp.data.success) {
                    $scope.contactinfo = resp.data.data;
                } else {
                    alert("Can't fetch contacts list");
                }
            }, function errorCallback(resp) {
                console.log(resp);
            });
        }

        $scope.file_changed = function (element) {

            $scope.$apply(function (scope) {
                var photofile = element.files[0];
                console.log(photofile);
                var reader = new FileReader();
                reader.onload = function (e) {
                    $scope.$apply(function () {
                        $scope.contactinfo.picture = e.target.result;
                    });
                };
                reader.readAsDataURL(photofile);
            });
        };

        $scope.submitForm = function () {
            $http({
                url: api_url + "contact",
                method: "PUT",
                data: $scope.contactinfo,
                headers: request_header,
            }).then(function successCallback(resp) {
                if (resp.data.success) {
                    $location.path("#contact_list");
                } else {
                    alert("Can't add contact");
                }
            }, function errorCallback(resp) {
                console.log(resp);
            });
        };

        $scope.saveChanges = function () {
            $http({
                url: api_url + "contact/index/id/" + $scope.contactId,
                method: "POST",
                data: $scope.contactinfo,
                headers: request_header,
            }).then(function successCallback(resp) {
                console.log($scope.contactinfo);

                if (resp.data.success) {
                    $location.path("#contact_list");
                } else {
                    alert("Can't add contact");
                }
            }, function errorCallback(resp) {
                console.log(resp);
            });
        };
    });

