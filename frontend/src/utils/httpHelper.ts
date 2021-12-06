import { AxiosResponse } from 'axios';
import { REDIRECT_URL } from '../constants';

class HttpHelper {
  private store: any;
  private seStore: any;

  constructor() {
    this.store = localStorage;
    this.seStore = sessionStorage;
  }

  /* global $msg */
  public successHelper(res: AxiosResponse<any>): void {
    const url = res.config.url.split('?')[0];
    switch (res.status) {
      case 200: {
        const path = url.split('/api');
        if (path[1] === '/login') {
          $msg.success('Вы успешно авторизовались');
        } else if (path[1] === '/logout') {
          this.store.clear();
          $msg.success('Вы вышли');
          setTimeout(() => {
            window.location.href = '/login';
          }, 1000);
        } else {
          $msg.success('Успешная операция');
        }
        break;
      }
      default: {
        break;
      }
    }
  }

  public errorHelper(err: AxiosResponse): void {
    // console.log(err, 'err.data.msg.statusText')
    const path = err.config.url.split('?')[0];
    // const data = err.data; // server response data
    switch (err.status) {
      case 400: {
        const arr = path.split('/api');
        if (arr[1] === '/login') {
          $msg.error('Неверное имя пользователя или пароль');
        } else if (arr[1] === '/upload-file') {
          $msg.error('Не удалось загрузить файл');
        }
        break;
      }
      case 401: {
        this.store.clear();
        $msg.error('Пожалуйста, войдите в систему еще раз');
        this.seStore.setItem(REDIRECT_URL, window.location.pathname);
        setTimeout(() => {
          window.location.href = '/login';
        }, 1000);
        break;
      }
      case 403: {
        $msg.error('Ошибка, доступ запрещен');
        break;
      }
      case 404: {
        $msg.error('Не найдено, ресурс не найден, пожалуйста, проверьте');
        break;
      }
      case 405: {
        $msg.error('Ошибка, этот метод не допускается');
        break;
      }
      case 406: {
        $msg.error('Ошибка, этот метод не принимается, пожалуйста, проверьте');
        break;
      }
      case 500: {
        $msg.error('Ошибка 500');
        break;
      }
      case 503: {
        $msg.error('Соединение отклонено, и услуга недоступна');
        break;
      }
      case 504: {
        $msg.error('Срок действия шлюза истекает');
        break;
      }
      default: {
        $msg.error('Ошибка, неизвестная ошибка на сервере');
        break;
      }
    }
  }
}

export default new HttpHelper();
