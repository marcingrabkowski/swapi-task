$('#search').on('change', function () {
    $.ajax({
        url: "{{url('/')}}/api/"+$('#type').val()+"/search/" + this.value + "",
        type: 'GET',
        dataType: 'json',
        success: function (res) {
            res = JSON.stringify(res);
            res = JSON.parse(res);

            var params = '';

            $('.data').empty();
            jQuery.each(res['data'], function (index, item) {

                if($('#type').val() == 'people') {
                    params += '<p>Name: ' + item['name'] + '</p>';
                    params += '<p>Gender: ' + item['gender'] + '</p>';

                    if(item['starships']) {
                        jQuery.each(item['starships'], function (key, value) {
                            jQuery.each(value, function (shipId, shipData) {
                                params += '<p><b>Ship #'+key+'</b>: ' + shipData['name'] + '</p>';
                                console.log(shipData);
                            });
                        });
                    }

                } else if($('#type').val() == 'starships') {
                    params += '<p>Name: ' + item['name'] + '</p>';
                    params += '<p>Model: ' + item['model'] + '</p>';
                } else {
                    params += '<p>Name: ' + item['name'] + '</p>';
                    params += '<p>Terrain: ' + item['terrain'] + '</p>';
                }

                var data = '<div class=col-lg-12" style="margin-top:20px"><div class="card">  <div class="card-body">' + params + '</div> </div> </div>';
                $(".data").append(data);
                data = '';
                params = '';
            });
        }
    });
});
$('#type').on('change', function () {
    $('#search').val('');
    $('.data').empty();
});
