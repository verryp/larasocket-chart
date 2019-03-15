<template>
  <div class="container">
    <div v-if="status != 'success'">
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

          <div v-if="status == 'error'" class="alert alert-danger">{{message}}</div>
        </div>
      </div>
    </div>

    <div class="mt-5" v-if="status == 'success'">
      <div class="row">
        <div class="col-md-6">
          <div class="alert alert-success">{{message}}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      feedback: "",
      status: "idle", // idle | submiiting | error | success
      message: ""
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
          this.$data.message = "Go Sanka Arigatōgozaimashita!";
        })
        .catch(error => {
          this.$data.status = "error";

          if (error.response.data.message) {
            this.$data.message = error.response.data.response;
          } else {
            this.$data.message = "terjadi error saat menyimpan data";
          }
        });
    }
  }
};
</script>
