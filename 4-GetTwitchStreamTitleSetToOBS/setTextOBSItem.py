import os
os.environ.pop('SSLKEYLOGFILE', None)

from obsws_python import ReqClient
from obsws_python.error import OBSSDKRequestError, OBSSDKAuthError, OBSSDKConnectionError
import sys
import asyncio
import json
import time

# .secretファイルからクライアントIDとシークレットキーを読み込む
with open('.secret', encoding='utf-8') as f:
    secret = json.load(f)

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
        print(f"設定エラー: secret.json に必要なキーが見つかりません: {e}")
        sys.exit(1)
    except OBSSDKAuthError:
        print("認証エラー: OBSのWebSocketパスワードが間違っています。")
        sys.exit(1)
    except (OBSSDKConnectionError, ConnectionRefusedError):
        print("接続エラー: OBSが起動していないか、ホスト/ポートの設定が間違っています。")
        sys.exit(1)
    except Exception as e:
        print(f"予期せぬエラーが発生しました: {e}")
        sys.exit(1)
async def obs_example():
    client = await obs_connect()

    try:
        client.set_input_settings(
            name=source_name,
            settings={"text": new_text},
            overlay=True # Trueにすると既存の設定を維持しつつ指定項目だけ上書き
        )

        print(f"テキストを更新しました: {source_name} -> {new_text}")
    except OBSSDKRequestError as e:
        print(f"エラーが発生しました: {e}")

source_name = secret['obs']['source_name']
new_text = f"Pythonから書き換えたテキストです!! {time.strftime('%Y-%m-%d %H:%M:%S')}"

if __name__ == "__main__":
    asyncio.run(obs_example())
