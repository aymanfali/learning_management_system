<template>
  <div>
    <h2>Hello from Vue Component!</h2>
    <button @click="count++">Clicked {{ count }} times</button>
  </div>
</template>

<script>
export default {
  name: 'ExampleComponent',
  data() {
    return {
      count: 0
    }
  }
}
</script>

<style scoped>
h2 {
  color: #42b983;
}
</style>
