
  <header class="header">
    <h1 class="header-title">Check your place </h1>
    <h2 class="header-headline">Vous n'y avez sûrement pas pensé.</h2>
    <p class="intro">Entrez ici le code postal de la commune pour trouver les informations clés.</p>
    <div class="main-form">
      <?php echo form_tag('@homepage') ?>
        <fieldset>
          <?php echo $form['q']->render(array('class' => 'main-form-input input', 'placeholder' => 'Code postal')) ?>
          <input type="submit" value="Ok" class="main-form-submit button"/>
          <?php echo $form->renderHiddenFields() ?>
        </fieldset>
      </form>
    </div>
  </header>

  <?php if ($sf_request->isMethod('post')): ?>

  <main class="main" role="main">

    <div class="information">

      <div class="information-left bloc-border">
        <h3>Nom ville</h3>
        <p>
          La ville "<?php echo $data['nom_commune'] ?>" a une superficie de "<?php echo $data['superficie'] ?>" km2 et une population de "<?php echo $data['population']*1000 ?>"
        </p>
      </div>

      <div class="information-right bloc-border">

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

        <h3>Evaluation</h3>
        <div class="note-final">
          <strong>4</strong>
          <span>Moyenne</span>
        </div>
        <ul class="note-details">
          <li>Loisirs<span class="review review-0">0</span></li>
          <li>Economie<span class="review review-1">1</span></li>
          <li>Santé<span class="review review-2">2</span></li>
          <li>Nature<span class="review review-3">3</span></li>
          <li>Pratique<span class="review review-4">4</span></li>
        </ul>
      </div>

    </div>

    <div class="">
    </div>

  </main>

<?php endif; ?>

  <footer class="footer">
    <p>by Mindpress</p>
  </footer>

<script type="text/javascript">
   function repoFormatResult(repo) {
      var markup = '<div class="row-fluid">' +
         '<div class="span10">' +
            '<div class="row-fluid">' +
               '<div class="span6">' + repo.nom_commune + '</div>' +
               '<div class="span3"><i class="fa fa-code-fork"></i> ' + repo.code_postal + '</div>' +
               '<div class="span3"><i class="fa fa-star"></i> ' + repo.departement + '</div>' +
            '</div></div></div>';

      return markup;
   }

   function repoFormatSelection(repo) {
    console.log(repo)
      return repo.code_insee;
   }

//  $(".main-form-input").select2({
//     placeholder: "Code postal",
//     minimumInputLength: 3,
//     ajax: {
//         url: "<?php echo url_for('search/search') ?>",
//         dataType: 'json',
//         quietMillis: 250,
//         data: function (term) { // page is the one-based page number tracked by Select2
//             return {
//                 q: term, //search term
//             };
//         },
//         results: function (data, page) {
//             return { results: data };
//         }
//     },
//     formatResult: repoFormatResult, // omitted for brevity, see the source of this page
//     formatSelection: repoFormatSelection, // omitted for brevity, see the source of this page
//     dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
//     escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
// });
</script>
