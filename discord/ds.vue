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
    if (this.getCurrentUserId() === this.adminId) {
      this.isAdmin = true;
    }
  },
  methods: {
    enableBot() {
      this.enableBotService();
      this.$router.push('/'); // Redirect to the main bot interface
    },
    getCurrentUserId() {
      return 'CURRENT_USER_ID';
    },
    enableBotService() {
      console.log('Bot enabled');
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
  color: red;
}
button {
  background-color: #4CAF50;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
button:hover {
  background-color: #3e8e41;
}
</style>