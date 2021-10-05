<html>
<head>
    <title>Swapi</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <script
        src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.js" integrity="sha512-Rd5Gf5A6chsunOJte+gKWyECMqkG8MgBYD1u80LOOJBfl6ka9CtatRrD4P0P5Q5V/z/ecvOCSYC8tLoWNrCpPg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>    <link rel="stylesheet" href="https://wowjs.uk/css/libs/animate.css">
<body>
<img id="sun" src="http://img2.wikia.nocookie.net/__cb20090309234126/starwars/images/e/ee/DeathStar2.jpg">
<div id='earth-orbit'>
    <img id="earth" src="http://fractalsponge.net/bigger/150.jpg">
</div>
<div style=" position: absolute;
    width:1000px;
    top: 20%;
    left: 50%;
    transform: translate(-50%, -50%);">
<div class="form-group text-center wow bounceInUp">
    <div class="input-group">
        <input type="text" class="form-control" id="search" name="query" placeholder="Type name">
        <div class="input-group-prepend">
            <select class="form-control" id="type">
                <option value="people">People</option>
                <option value="starships">Starships</option>
                <option value="planets">Planets</option>
            </select>
            <span class="input-group-text" id="basic-addon1">🔍</span>
        </div>
    </div>
    <div class="data row" style="position:absolute; margin-top:100px; width:100%;">
    </div>
</div>
</div>
</body>
</html>
<canvas id="canvas">Canvas is not supported in your browser.</canvas>
<script>
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
                        params += '<p>Homeworld: ' + item['homeworld']['name'] + '</p>';
                        if(item['starships']) {
                            jQuery.each(item['starships'], function (key, value) {
                                params += '<p><b>Ship #'+key+'</b>: ' + value['name'] + '</p>';
                            });
                        }

                    } else if($('#type').val() == 'starships') {
                        params += '<p>Name: ' + item['name'] + '</p>';
                        params += '<p>Model: ' + item['model'] + '</p>';
                    } else {
                        params += '<p>Name: ' + item['name'] + '</p>';
                        params += '<p>Terrain: ' + item['terrain'] + '</p>';
                    }

                    var data = '<div class="col-lg-6 datadiv" style="margin-top:20px"><div class="card" style="height:100%">  <div class="card-body">' + params + '</div> </div> </div>';
                    $(".data").append(data);
                    $('.datadiv').addClass('wow bounceInUp');
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
</script>
<script>
    new WOW().init();
</script>
<script src="{{ asset('js/effects.js') }}"> </script>
