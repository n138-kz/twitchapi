import os
os.environ.pop('SSLKEYLOGFILE', None)

from twitchAPI.twitch import Twitch
from twitchAPI.helper import first
from twitchAPI.type import TwitchAuthorizationException, TwitchAPIException
from aiohttp import ClientConnectorError
import asyncio
import json
from obsws_python import ReqClient
from obsws_python.error import OBSSDKError, OBSSDKRequestError
import sys
import time

# .secretファイルからクライアントIDとシークレットキーを読み込む
with open(os.path.join(os.path.dirname(__file__), '.secret'), encoding='utf-8') as f:
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
            return channel_info.title
        else:
            print("チャンネル情報が見つかりませんでした。")
            return None
        await twitch.close()
    except KeyError as e:
        print(f"設定エラー: .secret に必要なキーがありません: {e}")
        return None
    except TwitchAuthorizationException:
        print("認証エラー: Client ID または Client Secret が間違っています。")
        return None
    except ClientConnectorError:
        print("ネットワークエラー: インターネット接続を確認してください。")
        return None
    except TwitchAPIException as e:
        print(f"Twitch API エラーが発生しました: {e}")
        return None
    except Exception as e:
        print(f"予期せぬエラーが発生しました: {e}")
        return None

async def obs_connect():
    try:
        client = ReqClient(
            host=secret['obs']['host'],
            port=secret['obs']['port'],
            password=secret['obs']['password']
        )
        print("OBS WebSocket への接続に成功しました！")
        return client
    except KeyError as e:
        print(f"設定エラー: .secret に必要なキーが見つかりません: {e}")
        sys.exit(1)
    except OBSSDKError as e:
        print(f"認証・接続エラー: OBSのWebSocketパスワードが間違っています。 {e}")
        sys.exit(1)
    except (ConnectionRefusedError, TimeoutError) as e:
        print(f"接続エラー: OBSが起動していないか、ホスト/ポートの設定が間違っています。 {e}")
        sys.exit(1)
    except Exception as e:
        print(f"予期せぬエラーが発生しました: {e}")
        sys.exit(1)

async def obs_example(new_text=None):
    client = await obs_connect()

    try:
        client.set_input_settings(
            name=secret['obs']['source_name'],
            settings={"text": new_text},
            overlay=True # Trueにすると既存の設定を維持しつつ指定項目だけ上書き
        )

        print(f"テキストを更新しました: {secret['obs']['source_name']} -> {new_text}")
    except OBSSDKRequestError as e:
        print(f"エラーが発生しました: {e}")

async def syncrun():
    twitch = await twitch_example()
    if not twitch:
        twitch = f"{time.strftime('%Y-%m-%d %H:%M:%S')}"

    await obs_example(new_text=twitch)

if __name__ == "__main__":
    asyncio.run(syncrun())
