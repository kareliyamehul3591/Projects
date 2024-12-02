<template>
  <div>
    <div class="card">
      <div class="card-header justify-content-between">
        <template v-if="title">{{ translate(title, title) }}</template>
        <template v-else>{{ translate("image", "Image") }}</template>
        <button
          type="button"
          class="btn btn-primary text-white btn-sm float-right"
          @click="openGalleryModal()"
        >
          <span class="fas fa-edit"></span>
        </button>
      </div>
      <div class="card-body text-center">
        <!-- TODO mediaFolderId je zapečen na 1, to ni nujno res -->
        <vue-transmit
          @processing="loading = true"
          @success="imageDropped"
          dragClass="border-blur-upload"
          uploadAreaClasses="border-upload"
          :clickable="false"
          :params="{ mediaFolderName: module.name || backupMediaFolderName }"
          sty
          tag="section"
          ref="uploader"
          :url="getFilePath()"
          :headers="getHeaders()"
        >
          <div v-if="loading" class="row">
            <b-spinner  class="mx-auto my-3" variant="primary" role="status"></b-spinner>
          </div>

          <div v-else-if="localImage">
            <div class="detach-img">
              <span @click="imageSelected(null)" class="fas fa-times"></span>
            </div>
            <img
              class="pb-2 img-background-color"
              :src="localImage.imageUrl"
              alt=""
              title=""
              @click="openGalleryModal"
              style="width: 100%; padding-bottom: 0rem !important"
            />
          </div>

          <div v-else>
            <img
              class="pb-2 img-fluid"
              src="@/assets/images/upload-png.png"
              alt=""
              title=""
              @click="openGalleryModal"
              style="width: 50%"
            />
          </div>

          <p class="align-middle mb-0 mt-2">
            {{ translate("drag-photos-here", "Drag photos here") }} <br />
            {{ translate("or", "or") }} <br />
            <button class="btn btn-link p-0 b-0" type="button" @click="openGalleryModal">
              {{translate("find-images-in-media-library", "find images in Media library")}}
            </button>
          </p>
        </vue-transmit>
      </div>
    </div>

    <attach-image ref="attach" @attach="imageFromMediaLibrary" />
  </div>
</template>
<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator';
import stores from '@/stores';
import AttachImage from '@/modules/Media/Attach.vue';
import axiosInstance from '@/helpers/axios';

@Component({
  components: {
    AttachImage,
  },
})
export default class NevronImageField extends Vue {
  @Prop()
  module!: IModule;

  @Prop({ default: null })
  image!: IMediaItem;

  @Prop({ default: null })
  title!: string;

  localImage: IMediaItem | null = null;

  loading = false;

  get backupMediaFolderName() {
    const routeName = this.module.routeName;
    if (routeName) {
      return `${routeName[0].toUpperCase()}${routeName.slice(1)}`;
    }
  }

  @Watch('image', {immediate: true})
  setLocalImage(image: IMediaItem) {
    this.localImage = JSON.parse(JSON.stringify(this.image));
  }

  getHeaders() {
    return stores.File.getHeader();
  }
  getFilePath(): string {
    return axiosInstance.defaults.baseURL + stores.File.uploadUrl();
  }

  imageDropped(meta: any, image: any) {
    this.imageSelected(image);
  }

  imageFromMediaLibrary(result: any) {
    this.imageSelected(result.result);
  }

  imageSelected(image: IMediaItem) {
    this.localImage = image;
    this.loading = false;
    this.$emit('selected', image);
  }

  openGalleryModal() {
    // @ts-ignore
    this.$refs.attach.$children[0].open();
  }
}
</script>
