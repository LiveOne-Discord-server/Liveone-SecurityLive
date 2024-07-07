<template>
  <div class="bot-disabled">
    <h1>Bot Disabled</h1>
    <p>The bot is currently disabled and will not respond to messages.</p>
    <p>Only the server administrator can enable the bot.</p>
    <div v-if="isAdmin">
      <h2>Administrator Controls</h2>
      <button @click="enableBot">Enable Bot</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isAdmin: false,
      adminId: 'YOUR_ADMINISTRATOR_ID' // Replace with your server administrator's ID
    }
  },
  mounted() {
    // Check if the current user is the server administrator
    if (this.$discord.getUser().id === this.adminId) {
      this.isAdmin = true;
    }
  },
  methods: {
    enableBot() {
      // Send a request to enable the bot
      this.$discord.enableBot();
      this.$router.push('/'); // Redirect to the main bot interface
    }
  }
}
</script>

<style scoped>
.bot-disabled {
  text-align: center;
  padding: 20px;
}

h1 {
  color: #red;
}

button {
  background-color: #4CAF50;
  color: #white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button:hover {
  background-color: #3e8e41;
}
</style>