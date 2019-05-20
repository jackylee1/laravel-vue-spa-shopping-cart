<template>
  <div>
    <section class="media_content">
      <div class="container voucher_and_video">
        <div class="row">
          <div class="col-sm-6 voucher">
            <template v-if="certificate !== null">
              <router-link class="btn"
                           :to="{ name: 'catalog', query: { type: certificate.slug } }">
                <img class="voucher_img" src="/assets/public/images/voucher/voucher.png" alt="">
              </router-link>
            </template>
          </div>
          <div class="col-sm-6 video" v-for="file in indexMediaFiles">
            <youtube :fitParent="true"
                     :video-id="getVideoId(file.video)" ref="youtube"></youtube>
          </div>
        </div>
      </div>
    </section>

    <div class="modal fade" id="modalVideo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 centered">
                <h3 class="modal_title">Просмотр Видео</h3>
              </div>
            </div>
            <div class="row text-center" style="margin-bottom: 25px">
              <youtube v-if="videoFile !== null"
                       :fitParent="true"
                       :video-id="getVideoId(videoFile.video)" ref="youtube"></youtube>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'CertificatesVideo',
    mounted() {
      this.indexMediaFiles = this.indexMediaFilesStore;
      this.types = this.typesStore;
      this.findCertificate();
    },
    computed: {
      indexMediaFilesStore: function () {
        return this.$store.getters.indexMediaFiles;
      },
      typesStore: function () {
        return this.$store.getters.types;
      }
    },
    data() {
      return {
        indexMediaFiles: [],
        videoFile: null,
        types: null,
        certificate: null
      }
    },
    methods: {
      findCertificate: function () {
        if (this.types.length > 0) {
          let index = this.types.findIndex(item => item.show_on_certificate === 1);
          if (index !== -1) {
            this.certificate = this.types[index];
          }
        }
      },
      openModalVideo: function (file) {
        this.videoFile = file;
        $('#modalVideo').modal('show');
      },
      getVideoId: function (url) {
        return this.$youtube.getIdFromUrl(url);
      }
    },
    watch: {
      'indexMediaFilesStore': function (files) {
        this.indexMediaFiles = files;
      },
      'typesStore': function (types) {
        this.types = types;
        this.findCertificate();
      }
    }
  }
</script>