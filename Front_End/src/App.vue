<script>
export default {
  data() {
    return {
      count: 0,
    };
  },
  computed: {
    double() {
      return this.count * 2;
    },
    countArray() {
      const numbers = [];
      for (let i = 0; i < this.count; i++) {
        numbers.push(i);
      }
      return numbers;
    },
  },
  methods: {
    increment() {
      this.count++;
    },
    currentTime() {
      return new Date();
    },
  },
  mounted() {
    console.log(`The initial count is ${this.count}.`);
  },
};
</script>
<template>
  <!-- handle an event, alternate syntax v-on -->
  <button @click="increment">Count is: {{ count }}</button>
  <!-- use a computed property -->
  <p>Twice the count is: {{ double }}</p>
  <!-- call a method -->
  <p>The current date and time are: {{ currentTime() }}</p>
  <!-- use a conditional -->
  <p v-if="count % 2 === 0">The number is even</p>
  <p v-else>The number is odd</p>
  <p>Number count:</p>
  <!-- loop through an array, countArray is a computed property returning an array -->
  <div v-for="number in countArray">
    {{ number }}
  </div>
</template>
<style lang="css" scoped>
button {
  background-color: blue;
  color: white;
}
</style>
