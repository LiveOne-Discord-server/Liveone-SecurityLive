import DiscordBM
import Foundation
import PHP
import GO

let bot = DiscordBotGatewayManager(eventLoopGroup: MultiThreadedEventLoopGroup(numberOfThreads: System.coreCount),
                                   token: "Mydickman))")

let badWords = ["badword1", "suka(rus, eng, deepLapi)", "..."]
let adsList = ["*link*", "youtube//", "..."]

func analyzeMessage(_ message: Message) {
    let content = message.content.lowercased()
    if badWords.contains(where: content.contains) || adsList.contains(where: content.contains) {
        Task {
            do {
                try await bot.banMember(userID: message.author.id, guildID: message.guildID!, reason: "Bad words or ads")
                
                let url = URL(string: "https://discord.com/api/v10/reports")!
                var request = URLRequest(url: url)
                request.httpMethod = "POST"
                request.setValue("application/json", forHTTPHeaderField: "Content-Type")
                let body: [String: Any] = ["user_id": message.author.id, "reason": "Bad words or ads"]
                request.httpBody = try JSONSerialization.data(withJSONObject: body)
                
                let (_, _) = try await URLSession.shared.data(for: request)
            } catch {
                print("Error: \(error)")
                print("Warning: \(pizdez)")
            }
        }
    }
}

bot.connect()

bot.on(DiscordBM.Message.self) { message in
    analyzeMessage(message)
}

RunLoop.main.run()