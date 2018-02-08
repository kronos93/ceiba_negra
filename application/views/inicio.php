<main class="wrap-main">
    <section id="mapa">
        <div class="container-fluid container">
            <div class="row">
                <div class="col-xs-12 col-lg-12">
                    <div id="wrap">
                        <!-- Site content -->
                        <div id="content">
                            <section id="map-section" class="inner over">
                                <div class="editor-window">
                                    <div class="window-mockup brown"></div>
                                    <div class="editor-body">
                                        <code>
                                            {<br>
                                            &nbsp;&nbsp;&nbsp;"id": "newlandmark",<br>
                                            &nbsp;&nbsp;&nbsp;"title": "New Landmark",<br>
                                            &nbsp;&nbsp;&nbsp;"description": "Creating a new landmark is that easy!",<br>
                                            &nbsp;&nbsp;&nbsp;"x": "<span class="mapplic-coordinates-x">0.0000</span>",<br>
                                            &nbsp;&nbsp;&nbsp;"y": "<span class="mapplic-coordinates-y">0.0000</span>",<br>
                                            &nbsp;&nbsp;&nbsp;...<br>
                                            }
                                        </code>
                                    </div>
                                </div>
                                <div class="map-container">
                                    <div class="window-mockup">
                                        <div class="window-bar" data-terreno="La Ceiba"></div>
                                    </div>
                                    <!-- Map -->
                                    <div id="mapplic"></div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="container-fluid">
      <div class="row">
        <div class="col-md-4">
          <picture>
           <source srcset="<?= base_url() ?>assets/img/mapa.webp">
           <a href="<?= base_url() ?>assets/img/mapa.webp" target="blank">
            <img class="img-responsive" src="<?= base_url() ?>assets/img/mapa.jpg" alt="Mapa"/>
           </a>
          </picture>
        </div>
        <div class="col-md-8">
          <iframe src="https://www.google.com/maps/embed?pb=!1m20!1m8!1m3!1d17760.58127995965!2d-87.1219317847306!3d20.64098854361374!3m2!1i1024!2i768!4f13.1!4m9!3e0!4m3!3m2!1d20.6276433!2d-87.08368039999999!4m3!3m2!1d20.6640201!2d-87.146126!5e0!3m2!1sen!2smx!4v1517830457912" width="800" height="600" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>
      </div>
    </section>
</main>
