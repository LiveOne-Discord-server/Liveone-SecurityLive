import DiscordSwift
import PHP
import Go

let bot = DiscordBot(token: "YOUR_BOT_TOKEN")

let badWords = ["badword1", "badword2",...]
let adsList = ["ad1", "ad2",...]

func analyzeMessage(message: Message) {
    if badWords.contains(message.content) || adsList.contains(message.content) {
        bot.banUser(userID: message.author.id, reason: "Bad words or ads")

        let php = PHP()
        php.post(url: "https://discord.com/api/reports", data: ["user_id": message.author.id, "reason": "Bad words or ads"])
        
        let go = Go()
        go.post(url: "https://discord.com/api/reports", data: ["user_id": message.author.id, "reason": "Bad words or ads"])
    }
}

bot.onMessageCreate = { message in
    analyzeMessage(message: message)
}

bot.run()