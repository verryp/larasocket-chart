<template>
  <div class="container">
    <div v-if="status != 'success'">
      <div class="row mt-5">
        <div class="col-md-8 offset-md-2 text-center">
          <h1>Gambarkan dengan 3 kata, Bagaimana Visi anda terhadap BPN pada tahun {{ (new Date()).getFullYear() }}</h1>
        </div>
      </div>

      <div class="row mt-5">
        <div class="col-md-6">
          <input
            type="text"
            class="form-control form-control-lg"
            placeholder="contoh: keren top mantab"
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
      this.status = "submitting";

      this.axios
        .post("/api/feedback", {
          words: this.feedback
        })
        .then(response => {
          this.status = "success";
          this.message = "Go Sanka Arigatōgozaimashita!";
        })
        .catch(error => {
          this.status = "error";

          if (error.response.data.message) {
            this.message = error.response.data.response;
          } else {
            this.message = "terjadi error saat menyimpan data";
          }
        });
    }
  }
};
</script>
