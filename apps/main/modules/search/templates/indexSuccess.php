
  <header class="header">
    <div class="header-logo"><img src="images/logo-mindheader.png" alt="MindPress" width="150" /></div>
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

  <?php if ($sf_request->isMethod('post') && !empty($data)): ?>

  <main class="main" role="main">

    <div class="information">

      <div class="information-left bloc-border">
        <h3><?php echo $data['nom_commune'] ?></h3>
        <p>
          La ville <?php echo $data['nom_commune'] ?> a une superficie de <?php echo $data['superficie'] ?> km2 et une population de <?php echo $data['population']*1000 ?> habitants.
        </p>
      </div>

      <div class="information-right bloc-border">

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
                 "title": "<?php echo $data['nom_commune'] ?>",
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

        <h3>Évaluation</h3>
        <div class="note-final">
          <strong><?php echo $data['stars']['all'] ?></strong>
          <span>Moyenne</span>
        </div>
        <ul class="note-details">
          <li>Pratique<span class="review review-<?php echo $data['stars']['pratique'] ?>"><?php echo $data['stars']['pratique'] ?></span></li>
          <li>Loisirs<span class="review review-<?php echo $data['stars']['loisirs'] ?>"><?php echo $data['stars']['loisirs'] ?></span></li>
          <li>Nature<span class="review review-<?php echo $data['stars']['nature'] ?>"><?php echo $data['stars']['nature'] ?></span></li>
          <li>Économie<span class="review review-<?php echo $data['stars']['economie'] ?>"><?php echo $data['stars']['economie'] ?></span></li>
          <li>Santé<span class="review review-<?php echo $data['stars']['sante'] ?>"><?php echo $data['stars']['sante'] ?></span></li>
        </ul>
      </div>

    </div>


      <div class="container-block">
        <div id="pie" class="block-small block block-left">

        </div>

        <div id="map" class="block block-medium block-right">

        </div>
      </div>

      <div class="container-block">
        <div id="charts" class="block block-medium block-left">

        </div>

        <div class="block-small block block-right">
          <img src="images/graph-3.jpg">
        </div>

      </div>

  </main>

<?php endif; ?>

    <footer class="footer">
      <p>MindPress</p>
      <ul>
        <li>Elodie Montel</li>
        <li>Jérémy Benoist</li>
        <li>Mohammed Zemri</li>
        <li>Adrien Sénécat</li>
        <li>Adeline Alart</li>
        <li>Hakim Harrari</li>
      </ul>
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

$(function () {

    $('#charts').highcharts({

        chart: {
            polar: true,
            type: 'line'
        },

        title: {
            text: 'Avantage de la ville',
            x: -80
        },

        pane: {
            size: '80%'
        },

        xAxis: {
            categories: [
            'Services de base',
            'Services spécialisés',
            'Activités sportives',
            'Emploi',
            'Salaire',
            'Nature',
            'Transports',
            'Nombre de médecins généralistes',
            'Accès aux soins',],
            tickmarkPlacement: 'on',
            lineWidth: 0
        },

        yAxis: {
            gridLineInterpolation: 'polygon',
            lineWidth: 0,
            min: 0
        },

        tooltip: {
            shared: true,
            pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
        },

        legend: {
            align: 'right',
            verticalAlign: 'top',
            y: 70,
            layout: 'vertical'
        },

        series: [{
            name: 'Moyenne',
            data: [
              <?php echo $data['stars']['access_proxi'] ?>,
              <?php echo $data['stars']['access_inter'] ?>,
              <?php echo $data['stars']['licence_sport'] ?>,
              <?php echo $data['stars']['emploi'] ?>,
              <?php echo $data['stars']['salaire'] ?>,
              <?php echo $data['stars']['espace_nature'] ?>,
              <?php echo $data['stars']['dist_travail'] ?>,
              <?php echo $data['stars']['access_doctor'] ?>,
              <?php echo $data['stars']['access_care'] ?>,],
            pointPlacement: 'on'
        }]

    });

$('#pie').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 0,//null,
            plotShadow: false
        },
        title: {
            text: 'Comparatif entre les éléments pratiques'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Comparatif',
            data: [
                ['Services de base',   <?php echo $data['stars']['access_proxi'] ?>],
                ['Services spécialisés',       <?php echo $data['stars']['access_inter'] ?>],
                {
                    name: 'Chrome',
                    y: 12.8,
                    sliced: true,
                    selected: true
                },
                ['Transports',    <?php echo $data['stars']['dist_travail'] ?>],
            ]
        }]
    });
});
</script>
