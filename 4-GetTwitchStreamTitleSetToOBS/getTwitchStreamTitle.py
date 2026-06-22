import os
os.environ.pop('SSLKEYLOGFILE', None)

from twitchAPI.twitch import Twitch
from twitchAPI.helper import first
import asyncio
import json

# .secretファイルからクライアントIDとシークレットキーを読み込む
with open('.secret') as f:
    secret = json.load(f)

async def twitch_get_user(twitch):
    user = await first(twitch.get_users(logins=secret['twitch']['logins']))
    return user

async def twitch_example():
    twitch = await Twitch(secret['twitch']['client_id'], secret['twitch']['client_secret'])
    user = await twitch_get_user(twitch)

    stream = await first(twitch.get_streams(user_id=[user.id]))
    if stream:
        print(stream.title)
    else:
        print("配信はオフラインです。")
    await twitch.close()

#実行
if __name__ == "__main__":
    asyncio.run(twitch_example())
