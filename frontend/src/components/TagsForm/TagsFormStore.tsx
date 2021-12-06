import { makeAutoObservable, runInAction } from 'mobx';
import $http from '../../utils/http';
import { IApiResponseCollection } from '../../types/types';

interface ITag {
  id: number;
  tagName: string;
}

export interface TagOption {
  id?: number;
  label: string;
}

export class TagsFormStore {
  tags: TagOption[] = [];
  selectedTags: TagOption[] = [];

  constructor() {
    makeAutoObservable(this);
  }

  addTags = (tag: ITag) => {
    if (!this.checkTag(tag.tagName)) {
      this.tags.push({
        id: tag.id,
        label: tag.tagName,
      });
    }
  };

  checkTag = (tagName: string) => {
    return this.tags.some((tag) => tag.label === tagName);
  };

  filterTags = (inputValue: string) => {
    return this.tags.filter((i) => i.label.toLowerCase().includes(inputValue.toLowerCase()));
  };

  addSelectedTags = (selectedTags: TagOption, action: string) => {
    if (action == 'create-option') {
      this.postNewTags(selectedTags.label);
    } else {
      this.selectedTags.push(selectedTags);
    }
  };

  getTagsWithoutID = () => {
    return this.selectedTags.filter((selectedTag) => !selectedTag.id);
  };

  fetchTags = (inputValue: string) => {
    return $http
      .get<ITag>('/api/tags')
      .then((res: any) => {
        const data: IApiResponseCollection[] = res;
        runInAction(() => {
          data['hydra:member'].map((tag) => {
            this.addTags(tag);
          });
        });
      })
      .then(() => {
        return this.filterTags(inputValue);
      });
  };

  postNewTags = (tagName: string) => {
    $http
      .post<ITag>('/api/tags', {
        tagName: tagName,
      })
      .then((res: any) => {
        this.selectedTags.push({
          id: res.id,
          label: tagName,
        });
        this.addTags(res);
      });
  };
}
