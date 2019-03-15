<template>
  <div class="container">
    <div>
      <div class="row mt-5">
        <div class="col-md-8 offset-md-2 text-center">
          <h1>Sebutkan 3 nama yang anda idolakan/idamkan di tahun {{ (new Date()).getFullYear() }}</h1>
        </div>
      </div>

      <div class="row mt-5">
        <div class="col-md-6 offset-md-3">
          <input
            type="text"
            class="form-control form-control-lg"
            placeholder="contoh: jerome makbeti atta"
            :value="feedback"
            @input="feedback = $event.target.value"
          >
          <br>
          <button
            :disabled="status == 'processing'"
            @click="submit"
            class="btn btn-primary btn-block btn-lg"
          >Submit</button>

          <br>

          <div v-if="status == 'success'" class="alert alert-success">
            Dashboard =>
            <a style="color:red" :href="url" target="_blank">Hasilnya cek disini!</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style src="cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css"></style>

<script>
import axios from "axios";

export default {
  data() {
    return {
      feedback: "",
      status: "idle", // idle | submiiting | error | success
      message: "",
      url: "http://larasocket-chart.test/feedback/dashboard"
    };
  },

  methods: {
    submit() {
      this.$data.status = "submitting";

      axios
        .post("/api/feedback", {
          words: this.$data.feedback
        })
        .then(response => {
          this.$data.status = "success";
          this.$toast.success({
            title: "Success",
            message: "Go Sanka Arigatōgozaimashita!"
          });
        })
        .catch(error => {
          this.$data.status = "error";

          if (error.response.data.message) {
            this.$toast.error({
              title: "Error",
              message: error.response.data.message
            });
          } else {
            this.$toast.error({
              title: "Error",
              message: "terjadi error saat menyimpan data"
            });
          }
        });
    }
  }
};
</script>
