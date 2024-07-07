use serenity::model::{channel::Message, id::UserId};

pub async fn handle_message(ctx: &serenity::Context, msg: &Message) -> Result<(), serenity::Error> {
    let admin_id = UserId(1234567890); 

    if msg.author.id != admin_id {
        return Ok(());
    }
    Ok(())
}