import { FC, useEffect } from 'react';
import { useRootStore } from '../../RootStateContext';
import { ActionMeta, OnChangeValue } from 'react-select';
import { TagOption } from './TagsFormStore';
import { observer } from 'mobx-react-lite';
import AsyncCreatableSelect from 'react-select/async-creatable';

const TagsForm: FC = observer(() => {
  const { tagsFormStore } = useRootStore();

  useEffect(() => {
    // tagsFormStore.fetchTags()
  }, []);

  const handleChange = (newValue: OnChangeValue<TagOption, true>, actionMeta: ActionMeta<TagOption>) => {
    const id = newValue.length - 1;
    tagsFormStore.addSelectedTags(newValue[id], actionMeta.action);
  };

  return (
    <div>
      <AsyncCreatableSelect
        className="mb-3"
        isMulti
        cacheOptions
        defaultOptions
        getOptionLabel={(e) => e.label}
        getOptionValue={(e) => String(e.id)}
        onChange={handleChange}
        loadOptions={tagsFormStore.fetchTags}
      />
    </div>
  );
});

export default TagsForm;
