
<?php echo form_tag('@homepage') ?>

  <?php echo $form ?>
  <input type="submit" value="hop">



</form>

<div id="data"></div>

<?php if (isset($data['InseeData'][0])): ?>
<div id="inseee">
  <p><?php echo $data['InseeData'][0]['access_proxi'] ?></p>
  <p><?php echo $data['InseeData'][0]['access_inter'] ?></p>
  <p><?php echo $data['InseeData'][0]['licence_sport'] ?></p>
  <p><?php echo $data['InseeData'][0]['emploi'] ?></p>
  <p><?php echo $data['InseeData'][0]['salaire'] ?></p>
  <p><?php echo $data['InseeData'][0]['espace_nature'] ?></p>
  <p><?php echo $data['InseeData'][0]['dist_travail'] ?></p>
  <p><?php echo $data['InseeData'][0]['access_doctor'] ?></p>
  <p><?php echo $data['InseeData'][0]['access_care'] ?></p>
</div>
<?php endif; ?>

<div id='map' style="height: 600px; width: 600px">tes</div>


<script type="text/javascript">
L.mapbox.accessToken = 'pk.eyJ1IjoiaGhhcnJhcmkiLCJhIjoiWFloSmpEVSJ9.L1zWxnT4sa6xKrC4ssycZQ';

  $( document ).ready(function() {
    $.get('http://public.opendatasoft.com/api/records/1.0/search?dataset=correspondance-code-insee-code-postal&facet=insee_com&refine.insee_com=<?php echo $data['code_insee'] ?>', function (data) {
      console.log(data.records[0].fields)
      $('#data').html('La ville "'+data.records[0].fields.nom_comm+'" a une superficie de "'+data.records[0].fields.superficie+'" km2 et une population de "'+(data.records[0].fields.population*1000)+'"');

      var geojson = [
       {
         "type": "Feature",
         "geometry": data.records[0].fields.geo_shape,
         "properties": {
           "title": "Mapbox DC",
           "description": "1714 14th St NW, Washington DC",
           "marker-color": "#fc4353 ",
           "marker-size": "large",
           "marker-symbol": "monument"
         }
       },
      ];

      L.mapbox.map('map', 'hharrari.kd7a191c')
       .setView([data.records[0].fields.geo_point_2d[0], data.records[0].fields.geo_point_2d[1]], 13)
       .featureLayer.setGeoJSON(geojson);
    })
  });

</script>
