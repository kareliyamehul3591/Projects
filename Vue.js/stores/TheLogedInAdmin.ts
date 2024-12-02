import Model from '@/stores/abstract/Model';
import Session from '@/stores/Session';
import axiosInstance from '@/helpers/axios';

interface ITokenMessage {
  type: 'request' | 'set';
  tokenName: string;
  token?: string;
}
const bc = new BroadcastChannel('token_channel');

// Logged in admin!!
export default class TheAdmin extends Model {
  id: number = 0;
  email: string = '';
  enabled: boolean = false;
  // TODO: ROle, and module rights..

  languageId: number = 0;

  private pToken: string = '';

  set token(value: string) {
    if (value === this.pToken) {
      return;
    }
    this.pToken = value;
    if (value) {
      console.log('Setting token to', value);
      sessionStorage.setItem(this.tokenName, value);
      axiosInstance.defaults.headers.common.Authorization = 'Bearer ' + value;
    } else if (axiosInstance.defaults.headers && axiosInstance.defaults.headers.common) {
      console.log('Deleting token token!', value);
      delete axiosInstance.defaults.headers.common.Authorization;
    }
    this.sendToken();
  }

  get token() {
    return this.pToken;
  }

  constructor() {
    super();
    this.token = '';
    if (this.tokenName !== '') {
      this.token = localStorage.getItem(this.tokenName) || sessionStorage.getItem(this.tokenName) || ''; // Load token
    }

    if (!this.token) {
      this.requestToken();
    }

    const that = this;
    // onmessage binds this to bc
    bc.onmessage = (ev: MessageEvent) => {
      console.log('Recieved msg', ev.data);
      const msg: ITokenMessage = ev.data;
      if (ev.data.tokenName !== this.tokenName) {
        console.log('Not for same project!');
        return;
      }
      if (msg.type === 'set') {
        that.token = msg.token || '';
      } else if (msg.type === 'request') {
        that.sendToken();
      }
    };
  }
  async me() {
    return axiosInstance.get(this.url()).then((response) => {
      return response.data;
    });
  }
  sendToken() {
    const sendMsg: ITokenMessage = {tokenName: this.tokenName, type: 'set', token: this.token};
    bc.postMessage(sendMsg);
  }

  requestToken() {
    const sendMsg: ITokenMessage = {tokenName: this.tokenName, type: 'request'};
    bc.postMessage(sendMsg);
  }

  urlRoot() {
    return '';
  }

  url() {
    return `/admins/me`;
  }

  setLanguage(languageId: any) {
    return axiosInstance.post(`/admins/language/${languageId}`)
    .then((response) => response.data);
  }

  login(username: string, password: string, rememberMe = false) {
    return axiosInstance.post('/admins/login', {
      password,
      username,
    })
    .then((response) => {
      this.token = response.data.accessToken;
      // TODO: Maks dodaj expiresAt, kokr jes vem ga do zdej še ne uporabljaš

      console.log(this.token);
      if (rememberMe) {
        localStorage.setItem(this.tokenName, this.token);
      } else {
        localStorage.removeItem(this.tokenName);
      }
      return response;
    })
    .catch((error) => {
      return Promise.reject(error.response);
    });
  }

  logout() {
    if (!this.isLoggedIn) {
      return Promise.resolve();
    }

    return axiosInstance.post('/admins/logout')
      .catch((e) => {
          console.error(e);
      })
      .then((reponse) => {
        localStorage.removeItem(this.tokenName);
        sessionStorage.removeItem(this.tokenName);
        this.token  = '';
      });
  }

  get tokenName() {
    if (Session.project === null) {
      return '';
    } else {
      return `${Session.project!.id}-token`;
    }
  }

  get isLoggedIn(): boolean {
    return !!this.token;
  }
}
