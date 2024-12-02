import Account from '@/stores/Account.ts';
import AdminCollection from '@/stores/Admins';
import BulkEdit from '@/stores/BulkEdit.ts';
import Customer from '@/stores/Customer.ts';
import Devices from '@/stores/Devices.ts';
import EPG from '@/stores/EPG.ts';
import File from '@/stores/File.ts';
import Folder from '@/stores/Folder.ts';
import GlobalConfig from '@/stores/GlobalConfig.ts';
import Location from '@/stores/Location.ts';
import ModulesCollection from './ModulesCollection';
import Package from '@/stores/Package.ts';
import PackageItems from '@/stores/PackageItem.ts';
import Session from '@/stores/Session';
import Master from '@/stores/Master';
import TheAdmin from '@/stores/TheLogedInAdmin';
import User from '@/stores/User.ts';
import Dashboards from '@/stores/Dashboard';
import Messages from '@/stores/Messages.ts';
import Setting from '@/stores/Setting.ts';
import Profile from '@/stores/Profile.ts';
import MobileComposer from '@/stores/composer/Mobile';
import STBComposer from '@/stores/composer/STB';
import Matomo from '@/stores/Matomo';
import Language from '@/stores/Language.ts';
import Stays from '@/stores/Stays.ts';
import Promotions from '@/stores/Promotions';
import Interests from '@/stores/Interests';
import Assets from '@/stores/assets';
import Locations from '@/stores/Locations';
import Library from '@/stores/Library';
import Media from '@/stores/Media';
import GuestFlow from '@/stores/GuestFlow';
import StayTypes from '@/stores/StayTypes';
import AssetCategory from '@/stores/AssetCategory';
import LocationCategory from '@/stores/LocationCategory';
import SocialMedia from '@/stores/SocialMedia';
import CommentProposal from '@/stores/CommentProposal';
import RatingPlatform from '@/stores/RatingPlatform';
import GFMobileComposer from '@/stores/composer/GFMobile';
const stores = {
  epg: new EPG(),
  profile: new Profile(),
  accounts: new Account(),
  BulkEdit: new BulkEdit(),
  Customer: new Customer(),
  Devices: new Devices(),
  Setting: new Setting(),
  globalConfig: GlobalConfig,
  Location: new Location(),
  Language: new Language(),
  admin: new TheAdmin(), // Logged in admin
  admins: new AdminCollection(),
  modules: new ModulesCollection(),
  session: Session, // Local session data
  master: new Master(),
  User: new User(),
  File: new File(),
  Folder: new Folder(),
  Package: new Package(),
  PackageItems: new PackageItems(),
  dashboards: new Dashboards(),
  Messages: new Messages(),
  mobileComposer: new MobileComposer(),
  STBComposer: new STBComposer(),
  Matomo: new Matomo(),
  Stays: new Stays(),
  StayTypes: new StayTypes(),
  Promotions: new Promotions(),
  Interests: new Interests(),
  Assets: new Assets(),
  AssetCategory: new AssetCategory(),
  Locations: new Locations(),
  LocationCategory: new LocationCategory(),
  Library: new Library(),
  Media: new Media(),
  SocialMedia: new SocialMedia(),
  CommentProposal: new CommentProposal(),
  RatingPlatfrom: new RatingPlatform(),
  GuestFlow: new GuestFlow(),
  gFMobileComposer: new GFMobileComposer(),
  image: require('@/assets/images/profile-img.png'),
};

export default stores;
