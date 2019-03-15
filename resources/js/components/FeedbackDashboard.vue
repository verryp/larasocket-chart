<template>
  <div class="container">
    <div class="row mt-5">
      <div style="position-text:align-center" class="col-md-12">
        <h1>Top Ten
          <br>
          <small style="font-size: 14px;">NB: Jika database penuh, data akan direset</small>
        </h1>
        <hr>
      </div>
    </div>

    <div class="row mt-5">
      <div class="col-md-5 offset-md-2">
        <pie-chart :chart-data="piechartData"/>
      </div>
      <!-- <div class="col-md-5 text-left">
        <ul style="list-style: none; padding:2">
          <li v-for="(item, index) in data" :key="index">
            <h1 v-if="index==0">
              {{item.word.toUpperCase()}}
              ({{item.count}})
            </h1>

            <h2 v-if="index==1">
              {{item.word.toUpperCase()}}
              ({{item.count}})
            </h2>

            <h3 v-if="index==2">
              {{item.word.toUpperCase()}}
              ({{item.count}})
            </h3>

            <h4 v-if="index > 2">
              {{item.word.toUpperCase()}}
              ({{item.count}})
            </h4>
          </li>
        </ul>
      </div>-->
    </div>
  </div>
</template>

<script>
import axios from "axios";
import palette from "google-palette";

export default {
  data() {
    return {
      data: [],
      status: "idle", //? //fetching | idle | error
      message: ""
    };
  },

  computed: {
    piechartData() {
      return {
        datasets: [
          {
            data: this.$data.data.map(a => a.count),
            backgroundColor: palette(
              ["qualitative"],
              this.$data.data.length
            ).map(function(hex) {
              return "#" + hex;
            })
          }
        ],

        labels: this.$data.data.map(a => a.word.toUpperCase())
      };
    }
  },

  mounted() {
    this.fetchInitialData();
    this.listenForChange();
  },

  methods: {
    fetchInitialData() {
      axios
        .get("/api/feedback/data")
        .then(res => {
          this.$data.data = res.data;
          this.$data.status = "success";
        })
        .catch(err => {
          this.$data.status = "error";
        });
    },

    listenForChange() {
      window.Echo.channel("larasocket-chart").listen(
        "FeedbackReceived",
        payload => {
          this.$data.data = payload;
        }
      );
    }
  }
};
</script>
