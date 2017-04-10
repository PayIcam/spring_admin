app.controller('GuestsCtrl', function($scope, $timeout, $rootScope, $http){
    ////////////////////////////////
    // Variables d'initialisation //
    ////////////////////////////////

    $scope.loader = false;
    $scope.keyword = "";

    $scope.guests = []

    $scope.getFormuleSoiree = function(guest) {
        if (guest.repas == 1) return 'Repas'
        else if(guest.buffet == 1) return 'Buffet'
        else return 'Soirée';
    }
    $scope.getCountGuest = function(guest) {
        if (guest.is_icam == 1 && guest.count_guests != undefined) return guest.count_guests
        else return "";
    }
    $scope.getGuests = function() {
        $http.get('admin_result_guests_soiree_json.php?keyword='+$scope.keyword).then(
            function (res) {
                $scope.guests = res.data; 
            }, function(res) {
                console.log(res); 
            });
    }

    

    // $scope.addAlert("Vous n'êtes pas connecté à Internet", "danger", 5000);
    // $scope.alertModal('Attention', "Vous n'êtes pas connecté à Internet");
});
