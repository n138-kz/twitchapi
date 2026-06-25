import os
os.environ.pop('SSLKEYLOGFILE', None)

from twitchAPI.twitch import Twitch
from twitchAPI.helper import first
from twitchAPI.type import TwitchAuthorizationException, TwitchAPIException
from aiohttp import ClientConnectorError
import asyncio
import json

# .secretファイルからクライアントIDとシークレットキーを読み込む
with open('.secret', encoding='utf-8') as f:
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
    try:
        twitch = await Twitch(secret['twitch']['client_id'], secret['twitch']['client_secret'])
        user = await twitch_get_user(twitch)

        channel_info = await twitch_get_streamsettings(twitch, broadcaster_id=user.id)
        if channel_info:
            print(channel_info.title)
        else:
            print("チャンネル情報が見つかりませんでした。")
        await twitch.close()
    except KeyError as e:
        print(f"設定エラー: .secret に必要なキーがありません: {e}")
    except TwitchAuthorizationException:
        print("認証エラー: Client ID または Client Secret が間違っています。")
    except ClientConnectorError:
        print("ネットワークエラー: インターネット接続を確認してください。")
    except TwitchAPIException as e:
        print(f"Twitch API エラーが発生しました: {e}")
    except Exception as e:
        print(f"予期せぬエラーが発生しました: {e}")
if __name__ == "__main__":
    asyncio.run(twitch_example())
