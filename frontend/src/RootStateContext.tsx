import React, { createContext, FC, useContext } from 'react';
import { NewTestStore } from './pages/NewTest/NewTestStore';
import { QuestionFormStore } from './components/Question/QuestionFormStore';
import { TagsFormStore } from './components/TagsForm/TagsFormStore';
import { TestListStore } from './components/Test/TestListStore';

type RootStateContextValue = {
  newTestStore: NewTestStore;
  questionFormStore: QuestionFormStore;
  tagsFormStore: TagsFormStore;
  testListStore: TestListStore;
};

const RootStateContext = createContext<RootStateContextValue>({} as RootStateContextValue);

const newTestStore = new NewTestStore();
const questionFormStore = new QuestionFormStore();
const tagsFormStore = new TagsFormStore();
const testListStore = new TestListStore();

export const RootStateProvider: FC<React.PropsWithChildren<Record<string, unknown>>> = ({ children }) => {
  return (
    <RootStateContext.Provider
      value={{
        newTestStore,
        questionFormStore,
        tagsFormStore,
        testListStore,
      }}
    >
      {children}
    </RootStateContext.Provider>
  );
};

export const useRootStore = (): RootStateContextValue => useContext(RootStateContext);
