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

async def twitch_get_streamsettings(twitch, broadcaster_id=None):
    stream_settings = await twitch.get_channel_information(broadcaster_id=broadcaster_id)
    if isinstance(stream_settings, list) and len(stream_settings) > 0:
        stream_settings = stream_settings[0]
    return stream_settings

async def twitch_example():
    twitch = await Twitch(secret['twitch']['client_id'], secret['twitch']['client_secret'])
    user = await twitch_get_user(twitch)

    channel_info = await twitch_get_streamsettings(twitch, broadcaster_id=user.id)
    if channel_info:
        print(channel_info.title)
    else:
        print("チャンネル情報が見つかりませんでした。")
    await twitch.close()

#実行
if __name__ == "__main__":
    asyncio.run(twitch_example())
