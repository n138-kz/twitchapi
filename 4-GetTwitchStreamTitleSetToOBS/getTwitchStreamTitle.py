import os
os.environ.pop('SSLKEYLOGFILE', None)

from twitchAPI.twitch import Twitch
from twitchAPI.helper import first
import asyncio
import json

# .secretファイルからクライアントIDとシークレットキーを読み込む
with open('.secret') as f:
    secret = json.load(f)

async def twitch_example():
    #Twitch developersで取得したクライアントIDとシークレットキーを入力する
    twitch = await Twitch(secret['twitch']['client_id'], secret['twitch']['client_secret'])
    #自身のTwitchユーザー名を入力
    #ここは表示名ではないことに注意
    user = await first(twitch.get_users(logins=secret['twitch']['logins']))
    #ユーザーidを取得する
    print(user.id)

    stream = await twitch.get_streams(user_id=[user.id])
    if stream:
        print(stream.title)
    await twitch.close()

#実行
if __name__ == "__main__":
    asyncio.run(twitch_example())
